<?php

namespace App\Controller\ecommerce;

use App\Entity\Attribut;
use App\Entity\Caracteristiques;
use App\Entity\CategorieProd;
use App\Entity\Date;
use App\Entity\Produit;
use App\Form\ecommerce\ProduitType;
use App\Repository\CategorieProdRepository;
use App\Repository\ProduitRepository;
use App\Services\ecommerce\Tools;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use function Symfony\Component\String\s;

/**
 * Class ProduitController
 * @Route("/back/ecommerce/produit")
 * @package App\Controller\ecommerce
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_back")
     * @return Response
     */
    public function index()
    {
        return $this->render("backend/ecommerce/produit/show.html.twig");
    }

    /**
     * @Route("/get_produit_back", name="get_produit_back")
     * @param Request $request
     * @param ProduitRepository $repo
     * @param Tools $tools
     * @return Response
     */
    public function show(Request $request,ProduitRepository $repo,Tools $tools)
    {
        $check = $request->get("_");
        $draw = $request->get("draw");

        if(!$check)
            die();
        $produits = $repo->dataTableProduits($request);
        $max = $repo->count([]);

        $data = [
            "draw"=> $draw,
            "recordsTotal"=> $max,
            "recordsFiltered"=> $max,
            "data"=> $produits,
        ];
        return $this->json($data, 200, [], [
            "groups"=>"show_list",
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);
    }

    /**
     * @Route("/add",name="add_produit")
     * @Route("/modif/{produit}",name="modify_produit")
     * @param EntityManagerInterface $manager
     * @param Produit|null $produit
     * @return Response
     */
    public function add(EntityManagerInterface $manager,Produit $produit=null)
    {
        if($produit==null)
            $produit = new Produit();

        $form = $this->createForm(ProduitType::class,$produit,[
            "action"=>$this->generateUrl("save_produit",["produit"=>$produit->getId()])
        ]);

        $extraData = [];
        $extraData["images"] = $produit->getImages();
        if($produit->getId()!=null)
        {
            $extraData["pa"] = implode(",",$produit->getProduitsAssocies());
            $extraData["attr"] = implode(",",$produit->getAttributs());
            $extraData["desc"] = $produit->getDescription();
        }

        return $this->render('backend/ecommerce/produit/add.html.twig', [
            "form"=>$form->createView(),
            "categories"=>$manager->getRepository(CategorieProd::class)->findAll(),
            "attributs"=>$manager->getRepository(Attribut::class)->findAll(),
            "extraData" => $extraData
        ]);
    }

    /**
     * @Route("/save/{produit}", name="save_produit")
     * @param Request $request
     * @param FileUploader $uploader
     * @param EntityManagerInterface $manager
     * @param Tools $tools
     * @param Produit|null $produit
     * @return JsonResponse
     */
    public function save(Request $request,FileUploader $uploader,EntityManagerInterface $manager,Tools $tools,Produit $produit=null)
    {
        if(!$request->isXmlHttpRequest())
            die();
        if($produit==null)
            $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit,[
            "action"=>$this->generateUrl("save_produit",["produit"=>$produit->getId()])
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            /**
             * récupération des données
             */
            $produitsAssocies = $request->get("produitAssocies","");
            $attributs = $request->get("attributs","");
            $description = $request->get("description");
            $imageFiles = $request->files->get("files",[]);
            $ordreImages = array_values(array_filter(explode(",",$request->get("ordre","")),function($value){
                return !is_null($value) && $value !== '';
            }));
            $imageNames = $produit->getImages();
            /**
             * les cas initial est que toutes les images soient supprimées c'est le cas d'un nouveau produit
             * si par la suite on recoit des images, on verifie l'ordre et les emplacements manquants sont affectés
             * à cette variable pour un remplacement pour les nouvelles images
             */
            $deletedImages = $tools->getOrdreImage();

            /** verification et suppression des anciennes images */
            if(!empty($ordreImages))
            {
                $deletedImages = [];
                foreach ($tools->getOrdreImage() as $ordre)
                {
                    if(!in_array($ordre,$ordreImages))
                    {
                        array_push($deletedImages,$ordre);
                        if(array_key_exists($ordre,$imageNames))
                        {
                            $uploader->deleteFile($imageNames[$ordre],"produit");
                            unset($imageNames[$ordre]);
                            $imageNames = array_values($imageNames);
                        }
                    }
                }
            }
            /**
             *  ajout des nouvelles images
             */
            for($i=0;$i<count($imageFiles);$i++)
            {
                /** @var UploadedFile $imageFile */
                $imageFile = $imageFiles[$i];
                $imageName = $uploader->upload($imageFile,"produit",$produit->getNom());
                $imageNames[array_shift($deletedImages)] = $imageName;
            }

            if($produit->getId()==null)
            {
                $date = (new Date())->setDateAjout(new \DateTime());
                $manager->persist($date);
                $produit->setDate($date);
            }
            $produit->getDate()->setDateModification(new \DateTime());

            $produit
                ->setImages($imageNames)
                ->setDescription($description)
                ->setAttributs(explode(",",$attributs))
                ->setProduitsAssocies(explode(",",$produitsAssocies))
            ;

            if(is_null($produit->getPrixPromo()))$produit->setPrixPromo(0);

            if($tools->isCaracteristiquesPersistable($produit->getCaracteristique()))
                $manager->persist($produit->getCaracteristique());
            else
                $produit->setCaracteristique(null);

            if($tools->isDimensionPersistable($produit->getDimension()))
                $manager->persist($produit->getDimension());
            else
                $produit->setDimension(null);

            $manager->persist($produit);
            $manager->flush();
            return $this->json(['success'=>["Sauvegardé"]]);
        }

        return $this->json(['errors'=>$tools->getFormErrorsTree($form)]);
    }


    /**
     * recupere les inputs supplementaires du formulaire en fonction de la catégorie choisie
     *
     * @Route("/get_form/{produit}", name="get_form_extend_prod")
     * @param Request $request
     * @param CategorieProdRepository $repCategorie
     * @param Produit|null $produit
     * @return JsonResponse
     */
    public function getForm(Request $request,CategorieProdRepository $repCategorie, Produit $produit = null)
    {
        $data = "";
        $idCategorie = $request->get("idCategorie");
        $categorie = $repCategorie->findOneBy(["id"=>$idCategorie]);

        if($categorie==null)
            die();
        if($produit==null)
            $produit = new Produit();

        $form = $this->createForm(ProduitType::class,$produit);

        if($categorie->getTypeCategorie()=="immobilier")
            $data = $this->renderView("backend/ecommerce/produit/formulaire-immobilier.html.twig",[
                'form'=>$form->createView()
            ]);

        return $this->json($data);
    }

    /**
     * @Route("/delete" , name="delete_produit")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FileUploader $uploader
     * @return JsonResponse
     */
    public function delete(Request $request,EntityManagerInterface $manager,FileUploader $uploader)
    {
        $id = $request->get("idProduit");
        /** @var Produit $produit */
        $produit = $manager->getRepository(Produit::class)->findOneBy(["id"=>$id]);
        if(!$produit)
            die();

        foreach ($produit->getImages() as $imgeName)
            $uploader->deleteFile($imgeName,"produit");

        $manager->remove($produit);
        if($produit->getDimension()!=null)$manager->remove($produit->getDimension());
        if($produit->getCaracteristique()!=null)$manager->remove($produit->getCaracteristique());
        if($produit->getDate()!=null)$manager->remove($produit->getDate());
        foreach ($produit->getAvis() as $avis)
            $manager->remove($avis);

        $manager->flush();

        return $this->json(["success"=>["produit supprimé"]]);
    }
}

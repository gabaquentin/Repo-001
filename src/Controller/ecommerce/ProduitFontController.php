<?php

namespace App\Controller\ecommerce;

use App\Entity\Attribut;
use App\Entity\CategorieProd;
use App\Entity\Date;
use App\Entity\Produit;
use App\Entity\Ville;
use App\Form\ecommerce\ProduitType;
use App\Repository\AvisRepository;
use App\Repository\CategorieProdRepository;
use App\Repository\ProduitRepository;
use App\Repository\VilleRepository;
use App\Services\ecommerce\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ProduitFontController
 * @Route("/front/ecommerce/produit")
 * @package App\Controller\ecommerce
 */
class ProduitFontController extends AbstractController
{
    /**
     * @Route("/", name="produit_font")
     * @param EntityManagerInterface $em
     * @param Tools $tools
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(EntityManagerInterface $em,Tools $tools)
    {
        return $this->render('frontend/ecommerce/produit/produit.html.twig', [
            'categories' => $em->getRepository(CategorieProd::class)->findAllCategories(),
            'villes' => $em->getRepository(Ville::class)->findAll(),
            'prixMax' => $em->getRepository(Produit::class)->getMaxPrice(),
            'typeTransaction' => $tools->getTypeTransaction(),
        ]);
    }

    /**
     * @Route("/show_products_front", name="show_products_front")
     * @param Request $request
     * @param ProduitRepository $repo
     * @param Tools $tools
     * @return JsonResponse
     */
    public function getProducts(Request $request,ProduitRepository $repo,Tools $tools)
    {
        $start = $request->get("start");

        $produits = $repo->showProductsFront($request);
        //dd($produits);
        $show = $start + count($produits);
        $itemsMax = $repo->countProductsFront($request);

        $data = [
            "itemsMax"=>$itemsMax,
            "itemsShow"=>$show,
            "data" => $produits,
            "showMore"=> (($itemsMax-$show)==0)?false:true,
        ];

        return $this->json($data, 200, [], [
            "groups"=>"show_list",
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);
    }

    /**
     * @Route("/details/{produit}", name="show_single_product_front")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AvisRepository $repoAvis
     * @param Produit|null $produit
     * @return Response
     */
    public function showSingleProduct(Request $request,EntityManagerInterface $em, AvisRepository $repoAvis, Produit $produit=null)
    {
        if($produit==null)
            die("404");
        $produitAssocices = [];
        $newArray = [];
        $produits = $produit->getProduitsAssocies();
        foreach ($produits as $pId)
        {
            $p = $em->getRepository(Produit::class)->findOneBy(["id"=>$pId]);
            if($p!=null)
            {
                array_push($produitAssocices,$p);
                array_push($newArray,$pId);
            }
        }

        if(!empty(array_diff($produits,$newArray)))
        {
            $produit->setProduitsAssocies($newArray);
            $em->persist($produit);
            $em->flush();
        }
        return $this->render('frontend/ecommerce/produit/single-product.html.twig', [
            'produit' => $produit,
            "produitAssocies"=>$produitAssocices,
            "avis" => $repoAvis->findBy([], ['datePublication'=>'desc']),
            "noteGlobale" => $repoAvis->moyenneDesAvis(),
        ]);
    }

    /**
     * @Route("/add" , name="add_product_front")
     * @Route("/modif/{produit}",name="modify_produit_front")
     * @param EntityManagerInterface $manager
     * @param Produit|null $produit
     * @return Response
     */
    public function addProductFront(EntityManagerInterface $manager,Produit $produit=null)
    {
        if ($produit == null)
            $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit, [
            "action" => $this->generateUrl("save_produit", ["produit" => $produit->getId()])
        ]);

        $extraData = [];
        $extraData["images"] = $produit->getImages();
        if ($produit->getId() != null) {
            $extraData["pa"] = implode(",", $produit->getProduitsAssocies());
            $extraData["attr"] = implode(",", $produit->getAttributs());
            $extraData["desc"] = $produit->getDescription();
        }

        return $this->render("frontend/ecommerce/produit/add-product.html.twig", [
            "form" => $form->createView(),
            "categories" => $manager->getRepository(CategorieProd::class)->findAll(),
            "attributs" => $manager->getRepository(Attribut::class)->findAll(),
            "extraData" => $extraData
        ]);
    }

    /**
     * @Route("/mes-produits", name="mes_produits_front")
     */
    public function myProducts()
    {
        return $this->render('frontend/ecommerce/produit/show-produit.html.twig', []);
    }
}

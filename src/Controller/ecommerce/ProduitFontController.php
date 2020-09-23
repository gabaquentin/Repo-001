<?php

namespace App\Controller\ecommerce;

use App\Entity\Attribut;
use App\Entity\CategorieProd;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\ecommerce\ProduitType;
use App\Repository\AvisRepository;
use App\Repository\ProduitRepository;
use App\Services\ecommerce\PackTools;
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
 * @package App\Controller\ecommerce
 */
class ProduitFontController extends AbstractController
{
    /**
     * @Route("/ecommerce/produit", name="show_products_front")
     * @param EntityManagerInterface $em
     * @param Tools $tools
     * @param PackTools $packTools
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(EntityManagerInterface $em,Tools $tools,PackTools $packTools)
    {
        $cats = $em->getRepository(CategorieProd::class)->findAllCategories();
        $categoriesProd = [];
        foreach ($cats as $cat) {
            $sc = [];
            $nbreProduitsCat = 0;
            foreach ($cat->getSousCategories() as $sousCategory) {
                $n = $em->getRepository(Produit::class)->countProductsFrontValid($sousCategory->getId());
                $nbreProduitsCat += $n;
                $sc[]=[
                    "sc" => $sousCategory,
                    "nbreProduits" => $n,
                ];
            }
            $categoriesProd[]=[
                "categorie" => $cat,
                "nbreProduits" => $nbreProduitsCat,
                "sc"=>$sc,
            ];

        }

        return $this->render('frontend/ecommerce/produit/all-products.html.twig', [
            'categories' => $categoriesProd,
            'villes' => $em->getRepository(Ville::class)->findAll(),
            'prixMax' => $em->getRepository(Produit::class)->getMaxPrice(),
            'typeTransaction' => $tools->getTypeTransaction(),
            'produitAssocies'=>$packTools->getBoostedProducts(null),
            'produit'
        ]);
    }

    /**
     * @Route("/ecommerce/produit/show_products_front", name="get_products_front")
     * @param Request $request
     * @param ProduitRepository $repo
     * @param Tools $tools
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getProducts(Request $request,ProduitRepository $repo,Tools $tools)
    {
        if(!$request->isXmlHttpRequest())
            die();

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
     * @Route("/ecommerce/produit/details/{produit}", name="show_single_product_front")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AvisRepository $repoAvis
     * @param Produit|null $produit
     * @return Response
     */
    public function showSingleProduct(Request $request,EntityManagerInterface $em, AvisRepository $repoAvis,Produit $produit=null)
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
            "avis" => $repoAvis->findBy([], ['dateModification'=>'desc']),
            "noteGlobale" => $repoAvis->moyenneDesAvis(),
        ]);
    }

    /**
     * @Route("/account/produit/mes-produits/add" , name="add_product_front")
     * @Route("/account/produit/mes-produits/modif/{produit}",name="modify_product_front")
     * @param PackTools $packTools
     * @param EntityManagerInterface $em
     * @param Produit|null $produit
     * @return Response
     */
    public function addProductFront(PackTools $packTools,EntityManagerInterface $em,Produit $produit=null)
    {
        /** @var User $user */
        $user = $this->getUser();
        if(!$user)
            die("page de connexion");

        if(!$packTools->canPost($user))
            die("page des packs");

        if ($produit == null)
            $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit, [
            "action" => $this->generateUrl("save_produit", ["produit" => $produit->getId()]),
        ]);

        $extraData = [];
        $extraData["images"] = $produit->getImages();
        if ($produit->getId() != null) {

            if($produit->getClient()!=$this->getUser())
                die("vous ne pouvez pas acceder à cette page");

            $extraData["pa"] =  $produit->getProduitsAssocies();
            $extraData["attr"] = implode(",", $produit->getAttributs());
            $extraData["desc"] = $produit->getDescription();
        }

        $cats = $em->getRepository(CategorieProd::class)->findSousCategories();
        $categoriesProd = [];
        foreach ($cats as $cat) {
            $categoriesProd[]=[
                "categorie" => $cat,
                "produits" => $em->getRepository(Produit::class)->FindValidProducts($cat->getId(),$produit->getId()),
            ];
        }

        return $this->render("frontend/ecommerce/produit/add-product.html.twig", [
            "form" => $form->createView(),
            "categories" => $categoriesProd,
            "attributs" => $em->getRepository(Attribut::class)->findAll(),
            "extraData" => $extraData
        ]);
    }

    /**
     * @Route("/account/produit/mes-produits", name="my_products_front")
     * @param PackTools $tools
     * @return Response
     */
    public function myProducts(PackTools $tools)
    {
        $user = $this->getUser();
        if(!$user)
            die("page de connexion");
        $infos = $tools->showUserPackDetails($user);
        return $this->render('security/frontend/my-products.html.twig', [
            "canPost" => (($infos["postes"]["total"] > 0)),
            "hasBoost" => (($infos["boost"]["total"] > 0)),
            "userPackInfo" => $infos,
        ]);
    }

    /**
     * @Route("/account/produit/get-user-produits", name="get_products_user_front")
     * @param ProduitRepository $rep
     * @param Request $request
     * @return JsonResponse
     */
    public function userProducts(ProduitRepository $rep,Request $request)
    {
        if(!$request->isXmlHttpRequest())
            die("dd");

        /** @var User $user */
        $user = $this->getUser();
        if(!$user)
            die();

        $start = $request->get("start");
        $max = $request->get("max");

        $produits = $rep->findBy(["Client"=>$user->getId()],null,$max,$start);
        $show = $start + count($produits);
        $itemsMax = $rep->count(["Client"=>$user->getId()]);

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
     * @Route("/account/produit/refresh-product-date/{product}",name="refresh_product_date")
     * @param Request $request
     * @param Produit|null $product
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function refreshProduct(Request $request,EntityManagerInterface $em,Produit $product=null)
    {
        if(!$request->isXmlHttpRequest())
            die("");
        if(!$product)
            die();

        $product->getDate()->setDateModification(new \DateTime());
        $em->persist($product);
        $em->flush();

        return $this->json(["success"=>["mise à jour"]]);
    }
}

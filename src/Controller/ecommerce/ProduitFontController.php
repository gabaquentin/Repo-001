<?php

namespace App\Controller\ecommerce;

use App\Entity\Date;
use App\Entity\Produit;
use App\Repository\AvisRepository;
use App\Repository\CategorieProdRepository;
use App\Repository\ProduitRepository;
use App\Services\ecommerce\Tools;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param CategorieProdRepository $repo
     * @return Response
     */
    public function index(CategorieProdRepository $repo)
    {
        return $this->render('frontend/ecommerce/produit/produit.html.twig', [
            'categories' => $repo->findAllCategories(),
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
        $show = $start + count($produits);
        $itemsMax = $repo->countProductsFront($request);
        //dd($itemsMax);
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

}

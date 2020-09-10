<?php

namespace App\Controller;

use App\Services\ecommerce\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class PackController extends AbstractController
{
    /**
     * @Route("/back/pack", name="pack_show_back")
     */
    public function index()
    {
        return $this->render('pack/show-pack-back.html.twig', [
            'controller_name' => 'PackController',
        ]);
    }

    /**
     * @Route("/ecommerce/produit/packs", name="pack_show_product_front")
     * @param Tools $tools
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function showPackProductFront(Tools $tools,EntityManagerInterface $em)
    {
        $categories = $em->getRepository("App:CategorieProd")->findAllCategories();
        $data = [
            [
                "id"=>1,
                "titre"=>"Packs d'insertions",
                "description"=>"Acheter des packs qui vous permettrons de poster vos annoces sur la boutique du site",
                "blaz"=>"/frontend/img/illustrations/align-justify.svg",
                "prixBase"=>"0 F CFA",
                "nbrPostes"=>[
                    "5"=>"5 Postes",
                    "20"=>"20 Postes",
                    "50"=>"50 Postes",
                    "100"=>"100 Postes",
                ]
            ],
            [
                "id"=>2,
                "titre"=>"Packs Boost",
                "description"=>"Ce pack met vos annonces toujours à la une. Vos annonces seront visibles dans toutes les catégories de la boutique",
                "blaz"=>"/frontend/img/illustrations/trending-up.svg",
                "prixBase"=>"0 F CFA",
                "durees"=>[
                    "1"=>"1 Jours",
                    "3"=>"3 Jours",
                    "7"=>"7 Jours",
                    "15"=>"15 Jours",
                    "30"=>"30 Jours",
                    "60"=>"60 Jours",
                    "120"=>"120 Jours",
                ]
            ],
        ];
        $dataUnit = [];
        foreach ($categories as $category) {
            foreach ($category->getSousCategories() as $sousCategory) {
                $dataUnit [] = [
                    "idCat"=>$sousCategory->getId(),
                    "durationUnit"=>$sousCategory->getUniteBoost(),
                    "postUnit"=>$sousCategory->getUniteAnnonce(),
                ];
            }
        }
        //$tools->setPackInfoContent($data);
        $data = $tools->getPackInfoContent();

        return $this->render('pack/show-pack-produit-front.html.twig',[
            "categories"=>$categories,
            "data" => $data,
            "infoUnit"=> json_encode($dataUnit),
        ]);
    }
}

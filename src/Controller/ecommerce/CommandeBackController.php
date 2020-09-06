<?php

namespace App\Controller\ecommerce;

use App\Repository\CommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ProduitController
 * @Route("/back/ecommerce/commande")
 * @package App\Controller\ecommerce
 */
class CommandeBackController extends AbstractController
{
    /**
     * @Route("/", name="commande_back")
     */
    public function index()
    {
        return $this->render('backend/ecommerce/commande/show_all_commande.html.twig');
    }

    /**
     * @Route("/get_produit_back", name="get_order_back")
     * @param Request $request
     * @param CommandesRepository $repo
     * @return Response
     */
    public function show(Request $request,CommandesRepository $repo)
    {
        $check = $request->get("_");
        $draw = $request->get("draw");

        if(!$check)
            die();
        $commande = $repo->dataTableCommande($request);
        $max = $repo->count([]);

        $data = [
            "draw"=> $draw,
            "recordsTotal"=> $max,
            "recordsFiltered"=> $max,
            "order"=> $commande,
        ];
        return $this->json($data, 200, [], [
            "groups"=>"show_list",
        ]);
    }
}

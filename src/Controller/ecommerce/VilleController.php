<?php

namespace App\Controller\ecommerce;

use App\Entity\Ville;
use App\Form\ecommerce\VilleType;
use App\Services\ecommerce\Tools;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VilleController
 * @Route("/back/ecommerce/ville")
 * @package App\Controller\ecommerce
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/get_ville_form", name="get_ville_form")
     */
    public function index()
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class,$ville,[
            "action"=>$this->generateUrl("save_ville")
        ]);
        return $this->json( $this->renderView('backend/ecommerce/produit/formulaire-ville.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/save", name="save_ville")
     * @param Request $request
     * @param Tools $tools
     * @return Response
     */
    public function save(Request $request,Tools $tools)
    {
        $manager = $this->getDoctrine()->getManager();
        $data = [
            "errors" => [],
            "success" => []
        ];
        $ville = new Ville();
        $form = $this->createForm(VilleType::class,$ville,[
            "action"=>$this->generateUrl("save_ville")
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($ville);
            $manager->flush();
            $data["success"] = ["La ville ".$ville->getVilles()." a été ajoutée"];
            $data["ville"] = $ville;
        }
        else
            $data["errors"] = $tools->getFormErrorsTree($form);

        return $this->json($data);
    }
}

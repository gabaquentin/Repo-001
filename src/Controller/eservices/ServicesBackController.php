<?php

namespace App\Controller\eservices;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServicesBackController extends AbstractController
{

    /**
     * @Route("/back/services", name="accueil_services")
     */
    public function accueil_services()
    {
        return $this->render('backend/eservices/accueil.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/services/categorie", name="categorie_services")
     */
    public function categorie_detail()
    {
        return $this->render('backend/eservices/categorie_detail.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }
}

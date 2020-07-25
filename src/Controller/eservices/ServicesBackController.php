<?php

namespace App\Controller\eservices;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServicesBackController extends AbstractController
{
    /**
     * @Route("/services/back", name="services_back")
     */
    public function index()
    {
        return $this->render('services_back/index.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/services", name="accueil_services")
     */
    public function accueil_services()
    {
        return $this->render('backend/eservices/accueil.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }
}

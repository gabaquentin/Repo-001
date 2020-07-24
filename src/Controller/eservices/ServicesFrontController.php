<?php

namespace App\Controller\eservices;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServicesFrontController extends AbstractController
{
    /**
     * @Route("/services/accueil",name="services_accueil")
     */
    public function accueil_services()
    {
        return $this->render("frontend/services/accueil.html.twig");
    }
}

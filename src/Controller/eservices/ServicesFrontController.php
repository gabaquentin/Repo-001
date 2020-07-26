<?php

namespace App\Controller\eservices;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServicesFrontController extends AbstractController
{
    /**
     * @Route("/front/services/accueil",name="services_accueil")
     */
    public function accueil_services()
    {
        return $this->render("frontend/eservices/accueil.html.twig");
    }

    /**
     * @Route("/front/services/categorie",name="categorie")
     */
    public function detail_categorie()
    {
        return $this->render("frontend/eservices/categorie.html.twig");
    }

    /**
     * @Route("/front/services/categorie/services",name="services")
     */
    public function liste_services_par_categorie()
    {
        return $this->render("frontend/eservices/services.html.twig");
    }

    /**
     * @Route("/front/services/categorie/servicex",name="service")
     */
    public function afficher_service()
    {
        return $this->render("frontend/eservices/service.html.twig");
    }
}

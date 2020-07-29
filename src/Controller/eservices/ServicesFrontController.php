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

    /**
     * @Route("/front/services/reponse",name="service_reponse")
     */
    public function afficher_demande()
    {
        return $this->render("frontend/eservices/reponse.html.twig");
    }
    /**
     * @Route("/front/services/prestation",name="service_prestation")
     */
    public function afficher_prestation()
    {
        return $this->render("frontend/eservices/prestation.html.twig");
    }
    /**
     * @Route("/front/services/prestation_client",name="service_prestation_client")
     */
    public function afficher_prestation_client()
    {
        return $this->render("frontend/eservices/prestation_client.html.twig");
    }

    /**
     * @Route("/front/services/categorie/servicex/detail_service",name="detail_service")
     */
    public function detail_service()
    {
        return $this->render("frontend/eservices/detail_service.html.twig");
    }
}

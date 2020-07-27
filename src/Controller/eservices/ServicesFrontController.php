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
        return $this->render("frontend/eservices/accueil.html.twig");
    }

    /**
     * @Route("/service/prestation",name="service_prestation")
     */
    public function afficher_prestation(){
        return $this->render("frontend/eservices/prestation.html.twig");
    }
    /**
     * @Route("/service/demande",name="service_demande")
     */
    public function afficher_demande(){
        return $this->render("frontend/eservices/demande.html.twig");
    }
    /**
     * @Route("/service/prestation_client",name="service_prestation_client")
     */
    public function afficher_prestation_client(){
        return $this->render("frontend/eservices/prestation_client.html.twig");
    }

}

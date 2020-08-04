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
     * @Route("/back/services/categorie", name="detail_categorie")
     */
    public function categorie_detail()
    {
        return $this->render('backend/eservices/categorie_detail.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/services/categoriex/services", name="liste_services")
     */
    public function services_par_categorie()
    {
        return $this->render('backend/eservices/services.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/services/categoriex/servicex/questions", name="liste_questions")
     */
    public function questions_par_service()
    {
        return $this->render('backend/eservices/questions_service.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }
}

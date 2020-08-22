<?php

namespace App\Controller\eservices;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServicesBackController extends AbstractController
{

    /**
     * @Route("/back/eservices", name="accueil_services")
     */
    public function accueil_services()
    {
        return $this->render('backend/eservices/accueil.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/categorie", name="detail_categorie")
     */
    public function categorie_detail()
    {
        return $this->render('backend/eservices/categorie_detail.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/categoriex/services", name="liste_services")
     */
    public function services_par_categorie()
    {
        return $this->render('backend/eservices/services.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/categoriex/servicex/questions", name="liste_questions")
     */
    public function questions_par_service()
    {
        return $this->render('backend/eservices/questions_service.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/categorie/nouveau", name="nouvelle_categorie")
     */
    public function ajouter_categorie()
    {
        return $this->render('backend/eservices/add-categorie.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/service/nouveau", name="nouveau_service")
     */
    public function ajouter_service()
    {
        return $this->render('backend/eservices/add-service.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/service/nouveau/questions", name="ajouter_questions")
     */
    public function ajouter_questions()
    {
        return $this->render('backend/eservices/add-questions-service.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * @Route("/back/eservices/service/blog",name="blog")
     */
    public function afficher_blog()
    {
        return $this->render("backend/eservices/blog.html.twig");
    }
}

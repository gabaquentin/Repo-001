<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index()
    {
        return $this->render('app/index.html.twig');
    }

    /**
     * @Route("/back", name="back")
     */
    public function indexBack()
    {
        return $this->render('backend/index.html.twig');
    }

    /**
     * @Route("/boutique", name="app_boutique")
     */
    public function indexBoutique()
    {
        return $this->redirectToRoute("show_products_front");
        return $this->render('app/boutique.html.twig');
    }

    /**
     * @Route("/services", name="app_services")
     */
    public function indexServices()
    {
        return $this->render('app/services.html.twig');
    }

    /**
     * @Route("/terrain", name="app_terrain")
     */
    public function indexTerrain()
    {
        return $this->render('app/terrain.html.twig');
    }
}

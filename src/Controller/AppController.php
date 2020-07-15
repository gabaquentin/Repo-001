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
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/back", name="back")
     */
    public function indexBack()
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/boutique", name="app_boutique")
     */
    public function indexBoutique()
    {
        return $this->render('app/boutique.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/services", name="app_services")
     */
    public function indexServices()
    {
        return $this->render('app/services.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/terrain", name="app_terrain")
     */
    public function indexTerrain()
    {
        return $this->render('app/terrain.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}

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
}

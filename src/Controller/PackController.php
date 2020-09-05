<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PackController extends AbstractController
{
    /**
     * @Route("/back/pack", name="pack_show_back")
     */
    public function index()
    {
        return $this->render('pack/show-pack-back.html.twig', [
            'controller_name' => 'PackController',
        ]);
    }
}

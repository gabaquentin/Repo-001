<?php

namespace App\Controller\ecommerce;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AvisController
 * @Route("/back/ecommerce/stats")
 * @package App\Controller\ecommerce
 */

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/", name="statistique")
     */
    public function index()
    {
        return $this->render('backend/ecommerce/statistiques/stats_produit.html.twig');
    }


}

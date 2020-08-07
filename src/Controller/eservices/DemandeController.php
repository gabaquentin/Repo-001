<?php

namespace App\Controller\eservices;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemandeController
 * @package App\Controller\eservices
 * @Route("/back/eservices")
 */

class DemandeController extends AbstractController
{
    /**
     * @Route("/demande", name="demande")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DemandeController.php',
        ]);
    }

    /**
     * page de création de demande
     * créer une nouvelle demande
     * @Route("/categorie/service/demande/nouvelle/{service}",name="nouvelle_demande")
     * @param int service
     * @return Response
     */
    public function creer_demande(int $service)
    {
        return $this->render("frontend/eservices/detail_service.html.twig");
    }
}

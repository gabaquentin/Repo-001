<?php

namespace App\Controller\eservices;

use App\Entity\CategorieService;
use App\Entity\Service;
use App\Form\eservices\CategorieServiceType;
use App\Form\eservices\ServiceType;
use App\Repository\CategorieServiceRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServicesBackController
 * @package App\Controller\eservices
 * @Route("/back/eservices")
 */
class ServicesBackController extends AbstractController
{

    /**
     * afficher la liste des catégories
     * @Route("/", name="accueil_services")
     * @param CategorieServiceRepository $repo
     * @return Response
     */
    public function accueil_services(CategorieServiceRepository $repo)
    {
        return $this->render('backend/eservices/accueil.html.twig', [
            'categories' => $repo->findAll(),
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * afficher les détails d'une catégorie
     * @Route("/categorie/{id}", name="detail_categorie_back")
     * @param int $id
     * @param CategorieServiceRepository $repo
     * @return Response
     */
    public function categorie_detail(int $id, CategorieServiceRepository $repo)
    {
        return $this->render('backend/eservices/categorie_detail.html.twig', [
            'categorie' => $repo->findOneBy(['id' => $id]),
            'categories' => $repo->findAll(),
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * afficher la liste des services en fonction de la catégorie en paramètre
     * @Route("/categoriex/services", name="liste_services")
     */
    public function services_par_categorie()
    {
        return $this->render('backend/eservices/services.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

}

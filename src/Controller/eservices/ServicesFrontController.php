<?php

namespace App\Controller\eservices;

use App\Repository\CategorieServiceRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServicesFrontController
 * @package App\Controller\eservices
 * @Route("/front/eservices")
 */

class ServicesFrontController extends AbstractController
{
    /**
     * page d'acceuil
     * affichage de la liste des catégories
     * @Route("/accueil",name="services_accueil")
     * @param CategorieServiceRepository $repo
     * @return Response
     */
    public function accueil_services(CategorieServiceRepository $repo)
    {
        return $this->render("frontend/eservices/accueil.html.twig", [
            'categories' => $repo->findAll(),
        ]);
    }

    /**
     * page de detail_categorie
     * afficher les détails d'une catégorie
     * @Route("/categorie/{id}",name="detail_categorie_front")
     * @param CategorieServiceRepository $repo
     * @param int $id
     * @return Response
     */
    public function detail_categorie(CategorieServiceRepository $repo, int $id)
    {
        return $this->render("frontend/eservices/categorie.html.twig", [
            'categorie' => $repo->findOneBy(['id'=> $id]),
            'categories' => $repo->findAll(),
        ]);
    }

    /**
     * page des services
     * afficher la liste des services par categorie
     * @Route("/categorie/services/{categorie}",name="services")
     * @param ServiceRepository $repoService
     * @param CategorieServiceRepository $repoCat
     * @param int $categorie
     * @return Response
     */
    public function liste_services_par_categorie(ServiceRepository $repoService, CategorieServiceRepository $repoCat, int $categorie)
    {
        return $this->render("frontend/eservices/services.html.twig", [
            'services' => $repoService->findBy(['CategorieService'=> $categorie]),
            'categorie' => $repoCat->findOneBy(['id'=> $categorie]),

        ]);
    }

    /**
     * page service
     * afficher les details d un service
     * @Route("/categorie/service/{service}",name="detail_service_front")
     * @param ServiceRepository $repo
     * @param int $service
     * @return Response
     */
    public function afficher_service(ServiceRepository $repo, int $service)
    {
        return $this->render("frontend/eservices/service.html.twig", [
            "service" => $repo->findOneBy(['id'=>$service]),
        ]);
    }

    /**
     * @Route("/reponse",name="service_reponse")
     */
    public function afficher_demande()
    {
        return $this->render("frontend/eservices/reponse.html.twig");
    }
    /**
     * @Route("/prestation",name="service_prestation")
     */
    public function afficher_prestation()
    {
        return $this->render("frontend/eservices/prestation.html.twig");
    }
    /**
     * @Route("/prestation_client",name="service_prestation_client")
     */
    public function afficher_prestation_client()
    {
        return $this->render("frontend/eservices/prestation_client.html.twig");
    }

    /**
     * @Route("/demandes_client",name="demandes_client")
     */
    public function liste_demandes_client()
    {
        return $this->render("frontend/eservices/demandes_client.html.twig");
    }
}

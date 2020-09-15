<?php

namespace App\Controller\eservices;

use App\Repository\CategorieServiceRepository;
use App\Repository\DemandeRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/accueil/{scroll_bool}",name="services_accueil")
     * @param CategorieServiceRepository $repo
     * @return Response
     */
    public function accueil_services(CategorieServiceRepository $repo, int $scroll_bool=0)
    {
        return $this->render("frontend/eservices/accueil.html.twig", [
            'categories' => $repo->findAll(),
            'scroll_bool'=>$scroll_bool,
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
     * page detail_service
     * afficher les details d un service
     * @Route("/categorie/service/detail/{service}",name="detail_service")
     * @param ServiceRepository $repo
     * @param int $service
     * @return Response
     */
    public function afficher_detail_service(int $service){
        return $this->render("frontend/eservices/detail_service.html.twig");
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
     * @Route("/demandes_client",name="service_demandes_client")
     */
    public function liste_demandes_client(DemandeRepository $repo)
    {
        return $this->render("frontend/eservices/demandes_client.html.twig",["demandes"=>$repo->findBy(["client"=>$this->getUser()])]);
    }

//    /**
//     * @Route("/demandes_client/{categorie}",name="service_demande_client_categorie")
//     * @param DemandeRepository $repo
//     */
//    public function charger_par_categorie(DemandeRepository $repo, ServiceRepository $repo2, CategorieServiceRepository $repo3, string $categorie){
//        $categorie_service=$repo3->findBy(["nom"=>$categorie]);
//        return $this->render("frontend/eservices/demandes_client.html.twig",["demandes"=>$repo->findBy([
//            "client"=>$this->getUser(),
//            "service"=>$repo2->findBy(["CategorieService"=>$categorie_service])])]);
//
//    }

    /**
     * @Route("/demandes_client/{categorie}/{keyword}",name="service_demandes_client_recherche")
     */
    public function  recherche_demandes_client (DemandeRepository $repo,$categorie,$keyword) {
        $id_user=$this->getUser()->getId();
        $demandes=$repo->find_demande_join_service($categorie,$keyword,$id_user);
        return new JsonResponse(array("demandes"=>$demandes));
    }
    /**
     * @Route("/front/eservices/demandes_client/{demande_id}",name="demande_details")
     */
    public function  afficher_demande_details (DemandeRepository $repo,$demande_id) {
        return $this->render("frontend/eservices/demande_detail.html.twig",["demande"=>$repo->find($demande_id)]);
    }
}

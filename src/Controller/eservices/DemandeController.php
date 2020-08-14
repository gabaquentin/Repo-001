<?php

namespace App\Controller\eservices;

use App\Entity\Demande;
use App\Entity\QuestionService;
use App\Entity\Utilisateur;
use App\Form\eservices\DemandeType;
use App\Repository\QuestionServiceRepository;
use App\Repository\ServiceRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class DemandeController
 * @package App\Controller\eservices
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
     * @Route("/front/eservices/categorie/service/demande/nouvelle/{service}",name="nouvelle_demande")
     * @param int service
     * @return Response
     */
    public function creer_demande(int $service,Request $request)
    {
        $Demande=new Demande();
        $form=$this->createForm(DemandeType::class,$Demande);
//        $form=$form->handleRequest($request);
        return $this->render("frontend/eservices/detail_service.html.twig",["service_id"=>$service,"form"=>$form->createView()]);
    }

    /**
     * retourne des questions au niveau de la demande d'un service
     * @Route("/front/eservices/categorie/service/demande/nouvelle/{service}/questions",name="service_questions")
     * @param ServiceRepository $repo
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int service
     * @return array
     */
    public function afficher_questions(QuestionServiceRepository $repo) : JsonResponse{

        return new JsonResponse(array("service_questions"=>$repo->findQuestions()));
    }

    /**
     * @Route("/front/eservices/categorie/service/demande/user",name="recuperer_utilisateur")
     * @param UtilisateurRepository $repo
     * @param Request $request
     * @return Utilisateur|null
     */
    public function obtenir_utilisateur(UtilisateurRepository $repo, Request $request)
    {
        $session=$request->getSession();
        $session->set("utilisateur",$repo->findUser()[0]);
       return new JsonResponse(array("utilisateur"=>$repo->findUser()[0]));
    }
}

<?php

namespace App\Controller\eservices;

use App\Entity\Demande;
use App\Entity\QuestionService;
use App\Entity\ReponseDemande;
use App\Entity\Utilisateur;
use App\Form\eservices\DemandeType;
use App\Repository\DemandeRepository;
use App\Repository\QuestionServiceRepository;
use App\Repository\ReponseDemandeRepository;
use App\Repository\ServiceRepository;
use App\Repository\UtilisateurRepository;
use App\Services\FileUploader;
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
    public function creer_demande(int $service, Request $request)
    {
        $Demande = new Demande();
        $form = $this->createForm(DemandeType::class, $Demande);
//        $form=$form->handleRequest($request);
        return $this->render("frontend/eservices/detail_service.html.twig", ["service_id" => $service, "form" => $form->createView()]);
    }

    /**
     * retourne des questions au niveau de la demande d'un service
     * @Route("/front/eservices/categorie/service/demande/nouvelle/{service}/questions",name="service_questions")
     * @param ServiceRepository $repo
     * @param Request $request
     * @param int service
     * @return array
     */
    public function afficher_questions(QuestionServiceRepository $repo): JsonResponse
    {
        return new JsonResponse(array("service_questions" => $repo->findQuestions()));
    }

    /**
     * @Route("/front/eservices/categorie/service/demande/user",name="recuperer_utilisateur")
     * @param UtilisateurRepository $repo
     * @param Request $request
     * @return Utilisateur|null
     */
    public function obtenir_utilisateur(UtilisateurRepository $repo, Request $request)
    {
        $session = $request->getSession();
        $session->set("utilisateur", $repo->findUser()[0]);
        return new JsonResponse(array("utilisateur" => $repo->findUser()[0]));
    }

    /**
     * @Route("/front/eservices/categorie/service/demande/{service}/ajout",name="demande_ajout")
     * @param DemandeRepository $repo
     * @param Request $request
     * @param EntityManagerInterface $em
     */
    public function ajouterDemande(DemandeRepository $repo, Request $request, EntityManagerInterface $em, FileUploader $uploader,int $service)
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form = $form->handleRequest($request);
        dd($this->getUser());
        if ($form->isValid() && $form->isSubmitted()) {
            $demande->setPhoto1($uploader->upload($form->get('photo1')->getData(), "service",$this->getUser()->getUsername()."-1-"));
            $demande->setPhoto2($uploader->upload($form->get('photo2')->getData(), "service",$this->getUser()->getUsername()."-2-"));
            $demande->setPhoto3($uploader->upload($form->get('photo3')->getData(), "service",$this->getUser()->getUsername()."-3-"));
            $demande->setPhoto4($uploader->upload($form->get('photo4')->getData(), "service",$this->getUser()->getUsername()."-4-"));
            $demande->setClient($this->getUser());
            $session = $request->getSession();
            $em->persist($demande);
            $em->flush();
            $session->set("demande", $demande);
            return $this->redirectToRoute("reponse_demande_ajout",["service"=>$service]);
        }

        return $this->redirectToRoute("nouvelle_demande",["service"=>$service]);

    }

    /**
     * @Route("/front/eservices/categorie/service/demande/{service}/recuperer_reponses",name="recuperer_reponses")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $service
     */
    public function recupererReponses(Request $request, EntityManagerInterface $em, int $service ){
        $reponses=json_decode($request->get("reponses"));
        $session = $request->getSession();
        $session->set("reponses", $reponses);
        return new JsonResponse("ok");
    }

    /**
     * @Route("/front/eservices/categorie/service/demande/{service}/reponse/ajout",name="reponse_demande_ajout")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int service
     */
    public function ajouterReponseDemande(EntityManagerInterface $em, Request $request, $service)
    {
        $session = $request->getSession();
        $reponses = $session->get("reponses");
        $demande = $session->get("demande");
        $reponse_demande = new ReponseDemande();
        foreach ($reponses as $reponse) {
            $reponse_demande->setDemande($demande);
            $reponse_demande->setQuestion($reponse->question);
            $reponse_demande->setReponses($reponse->reponse);
            $em->persist($reponse_demande);
            $em->flush();
        }
        return new JsonResponse("demandes et reponse  ajoutée dans la base de données");

    }

}

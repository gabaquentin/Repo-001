<?php

namespace App\Controller\eservices;

use App\Entity\CategorieService;
use App\Entity\Demande;
use App\Entity\QuestionService;
use App\Entity\ReponseDemande;
use App\Entity\Service;
use App\Entity\Utilisateur;
use App\Form\eservices\DemandeType;
use App\Repository\CategorieServiceRepository;
use App\Repository\DemandeRepository;
use App\Repository\QuestionServiceRepository;
use App\Repository\ReponseDemandeRepository;
use App\Repository\ServiceRepository;
use App\Repository\UtilisateurRepository;
use App\Security\LoginFormAuthenticator;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

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
     * retourne des questions au niveau de la demande d'un service
     * @Route("/front/eservices/categorie/service/demande/nouvelle/{service}/questions",name="service_questions")
     * @param ServiceRepository $repo2
     * @param QuestionServiceRepository $repo
     * @param int service
     * @return JsonResponse
     */
    public function afficher_questions(QuestionServiceRepository $repo, SerializerInterface $serializer, int $service)
    {
        $question_service = $repo->findBy(["service" => $service]);
        $json = $serializer->serialize($question_service, "json", ['groups' => ['group1']]);
        return new Response($json);
    }

    /**
     * @Route("/front/eservices/categorie/service/demande/{service}/ajout",name="demande_ajout")
     * @param DemandeRepository $repo
     * @param Request $request
     * @param EntityManagerInterface $em
     */
    public function ajouterDemande(DemandeRepository $repo, ServiceRepository $repo2, Request $request, EntityManagerInterface $em, FileUploader $uploader, int $service)
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setPhoto1($uploader->upload($form->get('photo1')->getData(), "demande", $this->getUser()->getUsername() . "-1-"));
            $demande->setPhoto2($uploader->upload($form->get('photo2')->getData(), "demande", $this->getUser()->getUsername() . "-2-"));
            $demande->setPhoto3($uploader->upload($form->get('photo3')->getData(), "demande", $this->getUser()->getUsername() . "-3-"));
            $demande->setPhoto4($uploader->upload($form->get('photo4')->getData(), "demande", $this->getUser()->getUsername() . "-4-"));
            $demande->setClient($this->getUser());
            $demande->setService($repo2->find($service));
            $session = $request->getSession();
            $em->persist($demande);
            $em->flush();
            $session->set("demande_id", $demande->getId());
            return $this->redirectToRoute("reponse_demande_ajout", ["service" => $service]);
        }

        return $this->render("frontend/eservices/detail_service.html.twig", ["service_id" => $service, "service" => $repo2->find($service), "form" => $form->createView()]);
    }

    /**
     * @Route("/front/eservices/categorie/service/demande/{service}/recuperer_reponses",name="recuperer_reponses")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $service
     */
    public function recuperer_reponses(Request $request, EntityManagerInterface $em, int $service)
    {
        $reponses = json_decode($request->get("reponses"));
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
    public function ajouter_reponse_demande(EntityManagerInterface $em, Request $request, $service, DemandeRepository $repo)
    {
        $session = $request->getSession();
        $reponses = $session->get("reponses");
        $demande_id = $session->get("demande_id");
        $demande = $repo->find($demande_id);
        foreach ($reponses as $reponse) {
            $reponse_demande = new ReponseDemande();
            $reponse_demande->setDemande($demande);
            $reponse_demande->setQuestion($reponse->question);
            $reponse_demande->setReponses($reponse->reponse);
            $em->persist($reponse_demande);
            $em->flush();
        }
        return $this->redirectToRoute("service_demandes_client");

    }

    /**
     * @Route("/front/eservices/categorie/service/demandes",name="demande_utilisateur")
     * @param DemandeRepository $repo
     */
    public function recuperer_demandes(DemandeRepository $repo)
    {
        return new JsonResponse(array("demandes" => $repo->findByUser($this->getUser()->getId())));
    }

    /**
     * @Route("/front/eservices/categorie/service/demandes/{id}/suppression",name="demande_suppression")
     * @param DemandeRepository $repo
     */
    public function supprimer_demande(DemandeRepository $repo, EntityManagerInterface $em, int $id)
    {
        $demande = $repo->find($id);
        $em->remove($demande);
        $em->flush();
        return new JsonResponse("ok");
    }

    /**
     * @Route("/front/eservices/categorie/service/demandes/{id}/service",name="demande_service")
     * @param DemandeRepository $repo
     */
    public function charger_service_demande(DemandeRepository $repo, int $id)
    {
        return new JsonResponse(array(
            "service_image" => $repo->find($id)->getService()->getImg(),
            "service_nom" => $repo->find($id)->getService()->getNom()));
    }


    /**
     * @Route("/front/eservices/categorie/service/demandes/{demande}/demande_reponses")
     * @param ReponseDemandeRepository $repo
     * @param SerializerInterface $serializer
     * @param int $demande
     */
    public function charger_reponses(ReponseDemandeRepository $repo, SerializerInterface $serializer, int $demande)
    {
        $reponse_demande = $repo->findBy(["demande" => $demande]);
        $json = $serializer->serialize($reponse_demande, "json", ['groups' => ['group1']]);
        return new Response($json);
    }

    /**
     * @Route("/front/eservices/categorie/service/demandes/reponse_modification")
     * @param ReponseDemandeRepository $repo
     * @param SerializerInterface $serializer
     * @param int $demande
     */
    public function modifier_reponses(ReponseDemandeRepository $repo, EntityManagerInterface $em, Request $request)
    {
        $reponses = json_decode($request->get("reponses"));
        foreach ($reponses as $reponse) {
            $reponse_demande = $repo->find($reponse->id);
            $reponse_demande->setReponses($reponse->reponses);
            $em->flush();
        }
        return new JsonResponse("ok");
    }

    /**
     * @Route("/front/eservices/categorie/service/demandes/{demande_id}")
     * @param ReponseDemandeRepository $repo
     * @param SerializerInterface $serializer
     * @param int $demande
     */
    public function recuperer_demande(DemandeRepository $repo, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, int $demande_id)
    {
        $demande = $repo->find($demande_id);
        $json = $serializer->serialize($demande, "json", ['groups' => ['group1']]);
        return new Response($json);
    }

    /**
     * @Route("/front/eservices/categorie/service/demandes/{demande_id}/modification")
     * @param ReponseDemandeRepository $repo
     * @param SerializerInterface $serializer
     * @param int $demande
     */
    public function modifier_demande(DemandeRepository $repo, EntityManagerInterface $em, Request $request, int $demande_id)
    {
        $demande = $repo->find($demande_id);
        $demande->setDescription($request->get("description"));
        $demande->setLocalisation($request->get("localisation"));
        $demande->setDate($request->get("date"));
        $date = new \DateTime($request->get("heure"));
        $demande->setHeure($date);
        $em->flush();
        return new JsonResponse("ok");

    }

}

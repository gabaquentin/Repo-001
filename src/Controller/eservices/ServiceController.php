<?php

namespace App\Controller\eservices;

use App\Entity\Service;
use App\Form\eservices\ServiceType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServiceController
 * @package App\Controller\eservices
 * @Route("/back/eservices")
 */

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="service")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ServiceController.php',
        ]);
    }

    /**
     * ajouter un nouveau service
     * @Route("/service/nouveau", name="nouveau_service")
     * editer un service
     * @Route("/service/edit/{service}", name="edit_service")
     * @param Request $request
     * @param FileUploader $uploader
     * @param EntityManagerInterface $em
     * @param Service|null $service
     * @return Response
     */
    public function ajouter_service(Request $request, FileUploader $uploader, EntityManagerInterface $em, Service $service=null)
    {
        if($service == null)
            $service = new Service();

        $form = $this->createForm(ServiceType::class, $service/*, [
            "action"=>$this->generateUrl("edit_categorie", ["categorie"=> $categorie->getId()])
        ]*/);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $service->setNom($form->get('nom')->getData());
            $service->setCategorieService($form->get('categorieService')->getData());
            $service->setDescription($form->get('description')->getData());
            //$imageFile = $request->files->get('files',[]);
            $imageName = $uploader->upload($form->get('imgfile')->getData(),"service",$service->getNom());
            $service->setImg($imageName);
            /*if($categorie->getId() == null){

            }*/
            $em->persist($service);
            $em->flush();
        }

        return $this->render('backend/eservices/add-service.html.twig', [
            'form'=> $form->createView(),
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * ajouter des questions à un service passé en paramètre
     * @Route("service/nouveau/questions", name="ajouter_questions")
     */
    public function ajouter_questions()
    {
        return $this->render('backend/eservices/add-questions-service.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }

    /**
     * qfficher la liste des questions en fonction du service en paramètre
     * @Route("/categoriex/servicex/questions", name="liste_questions")
     */
    public function questions_par_service()
    {
        return $this->render('backend/eservices/questions_service.html.twig', [
            'controller_name' => 'ServicesBackController',
        ]);
    }
}

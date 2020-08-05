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
     * afficher la liste des services en fonction de la catégorie en paramètre
     * @Route("/categoriex/services", name="liste_services")
     */
    public function services_par_categorie()
    {
        return $this->render('backend/eservices/services.html.twig', [
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

    /**
     * ajout et modification d'une categorie de service
     * @Route("/categorie/nouveau", name="nouvelle_categorie")
     * editer une categorie
     * @Route("/categorie/edit/{categorie}", name="edit_categorie")
     * @param Request $request
     * @param FileUploader $uploader
     * @param EntityManagerInterface $em
     * @param CategorieService|null $categorie
     * @return Response
     */
    public function ajouter_categorie(Request $request, FileUploader $uploader, EntityManagerInterface $em, CategorieService $categorie=null)
    {
        if($categorie == null)
            $categorie = new CategorieService();

        $form = $this->createForm(CategorieServiceType::class, $categorie/*, [
            "action"=>$this->generateUrl("edit_categorie", ["categorie"=> $categorie->getId()])
        ]*/);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $categorie->setNom($form->get('nom')->getData());
            $categorie->setCategorieParent($form->get('categorieParent')->getData());
            $categorie->setDescription($form->get('description')->getData());
            //$imageFile = $request->files->get('files',[]);
            $imageName = $uploader->upload($form->get('imgfile')->getData(),"service",$categorie->getNom());
            $categorie->setImg($imageName);
            /*if($categorie->getId() == null){

            }*/
            $em->persist($categorie);
            $em->flush();
        }

        return $this->render('backend/eservices/add-categorie.html.twig', [
            'form'=> $form->createView(),
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
}

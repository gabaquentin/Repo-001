<?php

namespace App\Controller\eservices;

use App\Entity\CategorieService;
use App\Form\eservices\CategorieServiceType;
use App\Repository\CategorieServiceRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategorieController
 * @package App\Controller\eservices
 * @Route("/back/eservices")
 */

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CategorieController.php',
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
}

<?php

namespace App\Controller\eservices;

use App\Entity\QuestionService;
use App\Entity\Service;
use App\Form\eservices\ServiceType;
use App\Repository\ServiceRepository;
use App\Services\ecommerce\Tools;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param Service|null $service
     * @return Response
     */
    public function ajouter_service(Service $service=null)
    {
        if($service == null)
            $service = new Service();

        $form = $this->createForm(ServiceType::class, $service, [
            "action"=>$this->generateUrl("save_service", ["service"=> $service->getId()])
        ]);

        return $this->render('backend/eservices/add-service.html.twig', [
            'form'=> $form->createView(),
            'controller_name' => 'ServicesBackController',
        ]);

    }

    /**
     *@Route("/save/{service}", name="save_service")
     * @param Request $request
     * @param FileUploader $uploader
     * @param EntityManagerInterface $em
     * @param Tools $tools
     * @param Service|null $service
     * @return JsonResponse
     */
    public function save_service(Request $request,FileUploader $uploader,EntityManagerInterface $em,Tools $tools,Service $service=null)
    {
        if($service == null)
            $service = new Service();

        $form = $this->createForm(ServiceType::class, $service, [
            "action"=>$this->generateUrl("save_service", ["service"=> $service->getId()])
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            dump($form);
            $service->setNom($form->get('nom')->getData());
            $service->setCategorieService($form->get('categorieService')->getData());
            $service->setDescription($form->get('description')->getData());
            $service->setNbreQuestions($form->get('nbreQuestions')->getData());
            //$imageFile = $request->files->get('files',[]);
            if(!(!empty($service->getImg()) && empty($form->get('imgfile')->getData())))
            {
                $imageName = $uploader->upload($form->get('imgfile')->getData(),"service",$service->getNom());
                $service->setImg($imageName);
            }
            $em->persist($service);
            $em->flush();

            return $this->json(['success'=>["Sauvegardé"]]);
            //return $this->redirectToRoute('ajouter_questions', ['service'=>$service->getId()]);
        }

        return $this->json(['errors'=>$tools->getFormErrorsTree($form)]);
    }

    /**
     * ajouter des questions à un service passé en paramètre
     * @Route("/service/nouveau/questions/{service}", name="ajouter_questions")
     * nbre => nombre de question service => id du service
     * @param int $service
     * @param ServiceRepository $repo
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function ajouter_questions(EntityManagerInterface $em, int $service, ServiceRepository $repo, Request $request)
    {
        $serv = $repo->findOneBy(['id'=>$service]);
        if($request->isMethod('POST')){
            $data = $request->request->all();
            //file_put_contents(($this->getParameter('questions_service_path')).'/questions_service.json', json_encode($data));
            for ($i=1; $i<=$serv->getNbreQuestions(); $i++)
            {
                $question = new QuestionService();
                $question->setService($serv);
                $question->setQuestion($data['question'.$i]);
                $question->setReponses($data['reponses'.$i]);
                $question->setTypeQuestion($data['typeQuestion'.$i]);
                if(isset($data['autre'.$i]))
                $question->setAutre("oui");

                $em->persist($question);
                $em->flush();
            }

            dump($data);
        }
        return $this->render('backend/eservices/add-questions-service.html.twig', [
            'service' => $serv,
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

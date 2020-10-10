<?php


namespace App\Controller\eservices;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\eservices\BlogType;
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BlogBackController
 * @package App\Controller\eservices
 * @Route("/eservice")
 */

class BlogFrontController extends  AbstractController
{
    /**
     * @var BlogRepository
     */
    private $repository;
    /**
     * @var CommentaireRepository
     */
    private $blogRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public  function __construct(BlogRepository $repository,CommentaireRepository $blogRepository,UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->blogRepository = $blogRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/blog",name="front_blog")
     */
    public function afficher_blog(Request $request, PaginatorInterface $paginator)
    {
        $properties = $this->repository->findAll();
        $properties2 = $this->repository->findBy(array(),array('vues'=>'DESC'),5);
        $blogs = $paginator->paginate(
        // Doctrine Query, not results
            $properties,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render("frontend/eservices/blog.html.twig",[
            "blogs"=>$blogs,
            'popular'=>$properties2
        ]);
    }

    /**
     * @Route("/singleblog/voirplus/{id}/{plus}",name="voirplus")
     */
    public function voirplus(Request $request,$id,$plus){
        $commentaires = $this->blogRepository->findBy(array("blog"=>$id,"reponse"=>null),array('id'=>"ASC"),5,$plus);
        if ($commentaires==null){
            $properties = $this->repository->find($id);
            return new JsonResponse([
                'contenue' => $this->renderView('frontend/eservices/commentaires.html.twig',[
                    "blog"=>$properties,
                    "commentaires"=>[],
                    'plus'=>$plus+5])
            ]);
        }
        foreach ($commentaires as $item){
            $reponse=$this->blogRepository->findBy(array("blog"=>$id,"reponse"=>$item->getId()));
            $reponses=[];
            foreach ($reponse as $rep) {
                $reponses[] = [
                    'id'=>$rep->getId(),
                    'libelle'=>$rep->getLibelle(),
                    'utilisateur'=>$this->userRepository->find($rep->getUtilisateur()),
                    'date'=>$rep->getDate()
                ];
            }
            $comments[]= [
                'id'=>$item->getId(),
                'libelle'=>$item->getLibelle(),
                'utilisateur'=>$this->userRepository->find($item->getUtilisateur()),
                'reponses'=>$reponses,
                'date'=>$item->getDate(),
            ];
        }
        $properties = $this->repository->find($id);
        return new JsonResponse([
            'contenue' => $this->renderView('frontend/eservices/commentaires.html.twig',[
                "blog"=>$properties,
                "commentaires"=>$comments,
                'plus'=>$plus+5])
        ]);
    }
    /**
     * @Route("/front/eservices/service/singleblog/add/{id}",name="front_single_blog_add_commentaire")
     */
    public function ajoutCommentaire(Request $request,$id){
        if(isset($_POST['valider'])){
            $commentaire= new Commentaire();
            $commentaire->setDate(new \DateTime());
            $commentaire->setBlog($_POST['blog']);
            $commentaire->setLibelle($_POST['libelle']);
            $commentaire->setUtilisateur($_POST['id']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $blog=$this->repository->find($id);
            $blog->setCommentaires($blog->getCommentaires()+1);
            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
        }
        else if(isset($_POST['repondre'])){
            $commentaire= new Commentaire();
            $commentaire->setDate(new \DateTime());
            $commentaire->setBlog($_POST['blog']);
            $commentaire->setLibelle($_POST['libelle']);
            $commentaire->setUtilisateur($_POST['id']);
            $commentaire->setReponse($_POST['idCom']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            $blog=$this->repository->find($id);
            $blog->setCommentaires($blog->getCommentaires()+1);
            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
        }
        else if(isset($_POST['modifier'])){
            $commentaire= $this->blogRepository->find($_POST['idCom']);
            $commentaire->setLibelle($_POST['libelle']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
        }
        else if(isset($_POST['supprimer'])){
            $commentaire= $this->blogRepository->find($_POST['idCom']);
            $reponsess=$this->blogRepository->findBy(array('reponse'=>$commentaire->getId()));
            $em=$this->getDoctrine()->getManager();
            foreach ($reponsess as $rep)
            {
                $em->remove($rep);
                $em->flush();
            }
            $em->remove($commentaire);
            $em->flush();
        }
        $comments=[];
        $commentaires = $this->blogRepository->findBy(array("blog"=>$id,"reponse"=>null),array(),5,6);
        foreach ($commentaires as $item){
            $reponse=$this->blogRepository->findBy(array("blog"=>$id,"reponse"=>$item->getId()));
            $reponses=[];
            foreach ($reponse as $rep) {
                $reponses[] = [
                    'id'=>$rep->getId(),
                    'libelle'=>$rep->getLibelle(),
                    'utilisateur'=>$this->userRepository->find($rep->getUtilisateur()),
                    'date'=>$rep->getDate(),
                ];
            }
            $comments[]= [
                'id'=>$item->getId(),
                'libelle'=>$item->getLibelle(),
                'utilisateur'=>$this->userRepository->find($item->getUtilisateur()),
                'reponses'=>$reponses,
                'date'=>$item->getDate(),
            ];
        }
        $properties = $this->repository->find($id);
        $properties2 = $this->repository->findBy(array(),array('vues'=>'DESC'),5);
        if (isset($_POST['ajax'])){
            return new JsonResponse([
                'contenue' => $this->renderView('frontend/eservices/commentaires.html.twig',[
                    "blog"=>$properties,
                    "commentaires"=>$comments])
            ]);
        }
        return $this->render("frontend/eservices/single_blog.html.twig",[
            "blog"=>$properties,
            "commentaires"=>$comments,
            'popular'=>$properties2
        ]);
    }
    /**
     * @Route("/single_blog/messages{id}",name="messages")
     */
    public function recupererMessages(Request $request,$id){

        $comments=[];
        $commentaires = $this->blogRepository->findBy(array("blog"=>$id,"reponse"=>null),array('id'=>"ASC"),5,0);
        foreach ($commentaires as $item){
            $reponse=$this->blogRepository->findBy(array("blog"=>$id,"reponse"=>$item->getId()));
            $reponses=[];
            foreach ($reponse as $rep) {
                $reponses[] = [
                    'id'=>$rep->getId(),
                    'libelle'=>$rep->getLibelle(),
                    'utilisateur'=>$this->userRepository->find($rep->getUtilisateur()),
                    'date'=>$rep->getDate(),
                ];
            }
            $comments[]= [
                'id'=>$item->getId(),
                'libelle'=>$item->getLibelle(),
                'utilisateur'=>$this->userRepository->find($item->getUtilisateur()),
                'reponses'=>$reponses,
                'date'=>$item->getDate(),
            ];
        }
        $properties = $this->repository->find($id);
            return new JsonResponse([
                'contenue' => $this->renderView('frontend/eservices/commentaires.html.twig',[
                    "blog"=>$properties,
                    "commentaires"=>$comments,
                    'plus'=>5])
            ]);
    }
    /**
     * @Route("/search/",name="search")
     */
    public function search(Request $request){
        $requestString= $request->get('q');
        $posts=$this->repository->findLike($requestString);
        if (!$posts){
            $result['posts']['error'] = 'Aucun blog trouvé';
        }
        else{
            $result['posts']=$this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    /**
     * @Route("/front/eservices/service/searchAll/",name="searchAll")
     */
    public function searchAll(Request $request, PaginatorInterface $paginator){
        $properties=$this->repository->findAllLike($_POST['mot']);
        if (!isset($_POST['mot']))
            $properties=$this->repository->findAll();
        $properties2 = $this->repository->findBy(array(),array('vues'=>'DESC'),5);
        $blogs = $paginator->paginate(
        // Doctrine Query, not results
            $properties,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render("frontend/eservices/blog.html.twig",[
            "blogs"=>$blogs,
            'popular'=>$properties2
        ]);
    }

    public function getRealEntities($posts){
        foreach($posts as $post){
            $realEntities[$post->getId()] = [$post->getImage(),$post->getNom()];

        }
        return $realEntities;
    }
    /**
     * @Route("/singleblog/{id}",name="front_single_blog")
     */
    public function afficher_single_blog(Request $request,$id)
    {
        $properties = $this->repository->find($id);
        if($properties==null){
            return $this->render("errorpages/404error.html.twig");
        }
        $properties2 = $this->repository->findBy(array(),array('vues'=>'DESC'),5);
        $properties->setVues($properties->getVues()+1);
        $em=$this->getDoctrine()->getManager();
        $em->persist($properties);
        $em->flush();
        $properties = $this->repository->find($id);
        $comments=[];
        $commentaires = $this->blogRepository->findBy(array("blog"=>$id,"reponse"=>null),array('id'=>"ASC"),5,0);
        foreach ($commentaires as $item){
            $reponse=$this->blogRepository->findBy(array("blog"=>$id,"reponse"=>$item->getId()));
            $reponses=[];
            foreach ($reponse as $rep) {
                $reponses[] = [
                    'id'=>$rep->getId(),
                    'libelle'=>$rep->getLibelle(),
                    'utilisateur'=>$this->userRepository->find($rep->getUtilisateur()),
                    'date'=>$rep->getDate(),
                ];
            }
            $comments[]= [
              'id'=>$item->getId(),
              'libelle'=>$item->getLibelle(),
              'utilisateur'=>$this->userRepository->find($item->getUtilisateur()),
              'reponses'=>$reponses,
              'date'=>$item->getDate(),
            ];
        }
        return $this->render("frontend/eservices/single_blog.html.twig",[
            "blog"=>$properties,
            "commentaires"=>$comments,
            'popular'=>$properties2,
            "user"=>$this->getUser(),
            'plus'=>5
        ]);
    }
}
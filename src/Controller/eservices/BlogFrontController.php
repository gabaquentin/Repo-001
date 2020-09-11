<?php


namespace App\Controller\eservices;

use App\Form\eservices\BlogType;
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\WhereInWalker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BlogBackController
 * @package App\Controller\eservices
 * @Route("/front/eservice")
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

    public  function __construct(BlogRepository $repository,CommentaireRepository $blogRepository)
    {
        $this->repository = $repository;
        $this->blogRepository = $blogRepository;
    }

    /**
     * @Route("/front/eservices/service/blog",name="front_blog")
     */
    public function afficher_blog(Request $request, PaginatorInterface $paginator)
    {
        $properties = $this->repository->findAll();
        $blogs = $paginator->paginate(
        // Doctrine Query, not results
            $properties,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render("frontend/eservices/blog.html.twig",[
            "blogs"=>$blogs
        ]);
    }

    /**
     * @Route("/front/eservices/service/singleblog/{id}",name="front_single_blog")
     */
    public function afficher_single_blog(Request $request,$id)
    {
        if(isset($_POST['valider'])){
            $commentaire= new Commentaire();
            $commentaire->setDate(new \DateTime());
            $commentaire->setBlog($_POST['blog']);
            $commentaire->setLibelle($_POST['libelle']);
            $commentaire->setUtilisateur(1);
            $em=$this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
        }
        $properties = $this->repository->find($id);
        $commentaires = $this->blogRepository->findBy(array("blog"=>$id));
        return $this->render("frontend/eservices/single_blog.html.twig",[
            "blog"=>$properties,
            "commentaires"=>$commentaires
        ]);
    }
}
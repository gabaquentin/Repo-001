<?php


namespace App\Controller\eservices;

use App\Form\eservices\BlogType;
use App\Entity\Populaire;
use App\Form\PopolaireType;
use App\Repository\PopulaireRepository;
use App\Repository\BlogRepository;
use App\Services\eservice\Tools;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * Class BlogBackController
 * @package App\Controller\eservices
 * @Route("/back/eservice")
 */
class BlogBackController extends AbstractController
{
    /**
     * @var BlogRepository
     */
    private $repository;
    /**
     * @var PopulaireRepository
     */
    private $populaireRepository;

    public  function __construct(BlogRepository $repository, PopulaireRepository $populaireRepository)
    {
        $this->repository = $repository;
        $this->populaireRepository = $populaireRepository;
    }

    /**
     * @Route("/", name="populaire_back")
     * @return Response
     */
    public function index(Request $request)
    {
        $blog=new Populaire();
        $form = $this->createForm(PopolaireType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('populaire_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $blog->setImage($newFilename);
                $blog->setDate(new \DateTime());
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
        }
        return $this->render("backend/eservices/populaires.html.twig",[
            "form"=>$form->createView()
        ]);
    }


    /**
     * @Route("/searchAll/",name="searchAllBack")
     */
    public function searchAll(Request $request, PaginatorInterface $paginator){
        $properties=$this->repository->findAllLike($_POST['mot']);
        if (!isset($_POST['mot']))
            $properties=$this->repository->findAll();
        $blogs = $paginator->paginate(
        // Doctrine Query, not results
            $properties,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render("backend/eservices/blog.html.twig",[
            "blogs"=>$blogs
        ]);
    }

    /**
     * @Route("/showPopulaire", name="get_populaire_back")
     * @param Request $request
     * @param PopulaireRepository $repo
     * @param Tools $tools
     * @return Response
     */
    public function show(Request $request,PopulaireRepository $repo,Tools $tools)
    {
        $check = $request->get("_");
        $draw = $request->get("draw");

        if(!$check)
            die();
        $produits = $repo->dataTablePopulaire($request);
        $max = $repo->count([]);

        $data = [
            "draw"=> $draw,
            "recordsTotal"=> $max,
            "recordsFiltered"=> $max,
            "data"=> $produits,
        ];
        return $this->json($data, 200, [], [
            "groups"=>"show_list",
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);
    }

    /**
     * @Route("/blog/supprimer{id}",name="back_blog_del")
     */
    public function supprimerBlog(Request $request, PaginatorInterface $paginator, $id){
        $blog=$this->repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();
        return $this->afficher_blog($request,$paginator);
    }

    /**
     * @Route("/blog",name="back_blog")
     */
    public function afficher_blog(Request $request, PaginatorInterface $paginator)
    {
        $blog=new Blog();
        $properties = $this->repository->findAll();
        $blogs = $paginator->paginate(
        // Doctrine Query, not results
            $properties,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render("backend/eservices/blog.html.twig",[
            "blogs"=>$blogs
        ]);
    }
    /**
     * @Route("/addblog/",name="add_blog")
     */
    public function addBlog(Request $request, PaginatorInterface $paginator){
        $blog=new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('blog_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $blog->setVues(0);
                $blog->setCommentaires(0);
                $blog->setImage($newFilename);
                $blog->setIdCreateur(1);
                $blog->setDate(new \DateTime());
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
            $properties = $this->repository->findAll();
            $blogs = $paginator->paginate(
            // Doctrine Query, not results
                $properties,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                10
            );
            return $this->render("backend/eservices/blog.html.twig",[
                "blogs"=>$blogs,
                'form' => $form->createView()
            ]);
        }
        return $this->render("backend/eservices/add_blog.html.twig",[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/singleblog/{id}",name="back_single_blog")
     */
    public function afficher_single_blog(Request $request,$id)
    {
        $blog=$this->repository->find($id);
        if($blog==null){
            return $this->render("errorpages/404error.html.twig");
        }
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('blog_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $blog->setImage($newFilename);
                $blog->setIdCreateur(1);
                $blog->setDate(new \DateTime());
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
        }
        $properties = $this->repository->find($id);
        return $this->render("backend/eservices/single_blog.html.twig",[
            "blog"=>$properties,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/delete" , name="delete_popoplaire")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(Request $request,EntityManagerInterface $manager)
    {
        $id = $request->get("id");
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository(Populaire::class);
        $produit=$repo->find($id);
        $manager->remove($produit);

        $manager->flush();

        return $this->json(["success"=>["produit supprimé"]]);
    }
}
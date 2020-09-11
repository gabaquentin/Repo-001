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
     * @Route("/back/eservices/service/blog",name="back_blog")
     */
    public function afficher_blog(Request $request, PaginatorInterface $paginator)
    {
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
                $blog->setImage($newFilename);
                $blog->setIdCreateur(1);
                $blog->setDate(new \DateTime());
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
        }
        $properties = $this->repository->findAll();
        $blogs = $paginator->paginate(
        // Doctrine Query, not results
            $properties,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            1
        );
        return $this->render("backend/eservices/blog.html.twig",[
            "blogs"=>$blogs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/back/eservices/service/singleblog/{id}",name="back_single_blog")
     */
    public function afficher_single_blog(Request $request,$id)
    {
        $blog=$this->repository->find($id);
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
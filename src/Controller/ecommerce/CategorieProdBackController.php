<?php

namespace App\Controller\ecommerce;

use App\Entity\CategorieProd;
use App\Form\ecommerce\CategorieProdType;
use App\Repository\CategorieProdRepository;
use App\Services\ecommerce\Tools;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class CategorieProdBackController
 * @Route("/back/ecommerce/categories")
 * @package App\Controller\ecommerce
 */
class CategorieProdBackController extends AbstractController
{
    /**
     * page d'accueil des catégories
     *
     * @Route("/", name="category_prod")
     * @param CategorieProdRepository $prodRepository
     * @param Tools $tools
     * @return Response
     */
    public function index(CategorieProdRepository $prodRepository, Tools $tools)
    {
        return $this->render('backend/ecommerce/categrie/categorieProd.html.twig', [
            'categories' => $prodRepository->findAllCategories(),
            'typeProduits' => $tools->getTypeProduit(),
        ]);
    }

    /**
     * sauvegarde les madifications sur les anciennes catégories et les nouvelles
     *
     * @Route("/save/{category}", name="save_category")
     * @param Request $request
     * @param Tools $tools
     * @param CategorieProd $category
     * @return JsonResponse | RedirectResponse
     */
    public function saveCategory(Request $request,Tools $tools,CategorieProd $category = null)
    {
        $data = [
            "succes" => []
        ];
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository(CategorieProd::class);

        if($category==null)
            $category = (new CategorieProd())->setOrdre(0);

        $form = $this->createForm(CategorieProdType::class, $category,[
            'action'=>$this->generateUrl("save_category",["category"=>($category==null)?$category->getId():null]),
        ]);

        /**
         *  modifications sur la dispostion
         */
        $categories = $request->get("categories");

        if ($categories != null && !empty($categories)) {
            $i = 1;
            foreach ($categories as $cat) {
                $catId = $cat["id"];

                /** @var CategorieProd $category */
                $category = $repo->findOneBy(["id" => $catId]);

                if ($category != null) {
                    $category->setOrdre($i)->setCategorieParent(null);
                    $manager->persist($category);
                    $i++;

                    if (array_key_exists("children", $cat)) {
                        $j = 1;
                        foreach ($cat["children"] as $sc) {
                            $scId = $sc["id"];

                            /** @var CategorieProd $sousCategory */
                            $sousCategory = $repo->findOneBy(["id" => $scId]);
                            if ($sousCategory != null) {
                                $sousCategory->setOrdre($j)->setCategorieParent($category);
                                $manager->persist($sousCategory);
                                $j++;
                            }
                        }
                    }
                }
            }
            $manager->flush();
            $data["success"] = ["Catégories modifiées avec succès"];
            $data['form'] = $this->render('backend/ecommerce/categrie/formulaire.html.twig', [
                'form'=> $form->createView(),
            ]);
            return $this->json($data);
        }
        /**
         *  ajout et modifiation de nouvelles catégories
         */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($category);
            $manager->flush();
        }
        else
            return $this->json(["errors"=>$tools->getFormErrorsTree($form)]);

        return $this->json(["route"=>$this->generateUrl("category_prod")]);
    }

    /**
     * recupère le formulaire twig sous forme d'une chaine de caractères pour ajax ou le rend pour twif
     *
     * @Route("/get_category_template/{type}", name="get_categorie_template")
     * @param Request $request
     * @param CategorieProdRepository $repository
     * @param string $type
     * @return Response
     */
    public function getCategorieTemplate(Request $request, CategorieProdRepository $repository, $type = "string")
    {
        $idCategory = $request->query->get("idCategory", "empty");
        $category = null;

        if($idCategory!="empty")
            $category = $repository->findOneBy(["id" => $idCategory]);
        if ($category == null)
            $category = new CategorieProd();

        $form = $this->createForm(CategorieProdType::class, $category,[
            'action'=>$this->generateUrl("save_category",["category"=>$category->getId()]),
        ]);

        if($type == "twig")
            return $this->render('backend/ecommerce/categrie/formulaire.html.twig', [
                'form'=> $form->createView(),
            ]);

        return $this->json($this->renderView('backend/ecommerce/categrie/formulaire.html.twig', [
            'form'=> $form->createView(),
        ]));
    }

    /**
     * @Route("/delete_category", name="delete_category")
     * @param Request $request
     * @param CategorieProdRepository $repository
     * @return JsonResponse
     */
    public function DeleteCategorie(Request $request, CategorieProdRepository $repository)
    {
        $data = [
            "errors" => []
        ];
        $idCategory = $request->get("idCategory", null);
        if (!$idCategory)
            die();
        $category = $repository->findOneBy(["id" => $idCategory]);
        if (!$category)
            die();

        if(!$category->getProduits()->isEmpty())
            $data["errors"] = ["vous devez supprimer tous les produits de cette catégorie"];
        else
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->json($data);
    }

    /**
     * @Route("/get_category", name="get_categorie")
     * @param Request $request
     * @param CategorieProdRepository $repository
     * @return JsonResponse
     */
    public function getCategorie(Request $request, CategorieProdRepository $repository)
    {
        $idCategory = $request->query->get("idCategory", null);
        if (!$idCategory)
            die();

        sleep(3);

        return $this->json($repository->findOneBy(["id" => $idCategory]), 200, [], [
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);
    }
}

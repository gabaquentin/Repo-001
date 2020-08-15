<?php

namespace App\Controller\ecommerce;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AvisController
 * @Route("/front/ecommerce/produit")
 * @package App\Controller\ecommerce
 */

class AvisController extends AbstractController
{
    /**
     * @Route("/details/avis", name="noter_produit")
     * @param EntityManagerInterface $em
     * @param ProduitRepository $repoProduit
     * @param UserRepository $repoUser
     * @param AvisRepository $repoAvis
     * @param Request $request
     * @return JsonResponse
     */
    public function noter_produit(EntityManagerInterface $em, ProduitRepository $repoProduit, Request $request, UserRepository $repoUser, AvisRepository $repoAvis)
    {
        dump($this->getUser());
        if($request->isXmlHttpRequest()) {
            $message = '';
            $a = new Avis();
            $a = $repoAvis->findOneBy(['produit'=>$request->request->get('idproduit'), 'client'=>1]);
            if($a == null)
            {
                $avis = new Avis();
                //$avis->setClient($repoUser->findOneBy(['id'=>1]));
                $avis->setClient($repoUser->findOneBy(['id'=>1]));
                $avis->setCommentaire($request->request->get('comment'));
                $avis->setNote($request->request->get('notes'));
                $avis->setProduit($repoProduit->findOneBy(['id'=>$request->request->get('idproduit')]));
                $avis->setDatePublication(new \DateTime());
                $em->persist($avis);
                $message = $message."<div class=\"customer-ratings\">\n".
                    "                                        <!-- Product review -->\n" .
                    "                                        <div class=\"media\">\n" .
                    "                                                <div class=\"media-left\">\n" .
                    "                                                        <figure class=\"image is-32x32\">\n" .
                    "                                                                <img src=\"http://via.placeholder.com/250x250\" data-demo-src=\"assets/img/avatars/elie.jpg\"\n" .
                    "                                                                     alt=\"\">\n" .
                    "                                                        </figure>\n" .
                    "                                                </div>\n" .
                    "                                                <div class=\"media-content\">\n" .
                    "                                                        <p>\n" .
                    "                                                                <span>Ngassa</span>\n".
                    "                                                                <small>\n";
                if($avis->getNote() == 0){
                    $message = $message."<i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 0.5){
                    $message = $message."<i class=\"fa fa-star-half\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 1){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 1.5){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-half-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 2){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 2.5){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-half-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 3){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 3.5){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-half-o\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 4){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-o\"></i>";
                        }
                if($avis->getNote() == 4.5){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star-half-o\"></i>";
                        }
                if($avis->getNote() == 5){
                    $message = $message."<i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>\n" .
                        "                                                                                <i class=\"fa fa-star\"></i>";
                        }
                $message = $message."</small>\n" .
                    "                                                                <br>\n" .
                    "                                                                <span class=\"rating-content\">".$avis->getCommentaire()."</span>\n" .
                    "                                                        </p>\n" .
                    "                                                </div>\n".
                    "<div class=\"media-right\"> ".
                                                    "<a href=\"#\" id=\"edit-rating\" class=\"add-review modal-trigger\" data-modal=\"review-modal\"><i class=\"fa fa-edit\"></i></a>".
           " </div>".
                    "                                        </div>\n" .
                    "                                </div>";
            }
            else
            {
                //$message = 'avis-modifie';
                $a->setCommentaire($request->request->get('comment'));
                $a->setNote($request->request->get('notes'));
                $a->setDateModification(new \DateTime());
                $em->persist($a);
            }

            $em->flush();
            $response = new JsonResponse();
            $response->setData(array('status'=> 'success', 'id'=>200, 'message'=>$message));
            //return new JsonResponse(array('a'=>$avis), 200);
            return $response;
        }
        else{
            $response = new JsonResponse();
            $response->setData(array('error'=> 'error'));
            return $response;
        }
    }

    /**
     * @Route("/details/avis/{idProduit}", name="supprimer_note")
     * @param int $idProduit
     * @param EntityManagerInterface $em
     * @param ProduitRepository $repoProduit
     * @param UserRepository $repoUser
     * @param AvisRepository $repoAvis
     * @param Request $request
     * @return JsonResponse
     */
    public function supprimer_note(EntityManagerInterface $em, ProduitRepository $repoProduit, Request $request, UserRepository $repoUser, AvisRepository $repoAvis, int $idProduit)
    {
        if($request->isXmlHttpRequest()) {
            $em->remove($repoAvis->findOneBy(['produit'=>$idProduit]));
            $em->flush();
            $response = new JsonResponse();
            $response->setData(array('status'=> 'success', 'id'=>200, 'message'=>''));
            //return new JsonResponse(array('a'=>$avis), 200);
            return $response;
        }else{
            $response = new JsonResponse();
            $response->setData(array('error'=> 'error'));
            return $response;
        }
    }
}

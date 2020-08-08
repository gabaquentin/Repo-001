<?php

namespace App\Controller\ecommerce;

use App\Entity\Avis;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function noter_produit(EntityManagerInterface $em, ProduitRepository $repoProduit, Request $request, UserRepository $repoUser)
    {
        dump($this->getUser());
        if($request->isXmlHttpRequest()) {
            $avis = new Avis();
            //$avis->setClient($repoUser->findOneBy(['id'=>1]));
            $avis->setClient($repoUser->findOneBy(['id'=>1]));
            $avis->setCommentaire($request->request->get('comment'));
            $avis->setNote($request->request->get('notes'));
            $avis->setProduit($repoProduit->findOneBy(['id'=>$request->request->get('idproduit')]));
            $avis->setDatePublication(new \DateTime());
            $em->persist($avis);
            $em->flush();
            $response = new JsonResponse();
            $response->setData(array('status'=> 'success', 'id'=>200, 'message'=>'avis enregistre'));
            return new JsonResponse(array('a'=>$avis), 200);
        }
        else{
            $response = new JsonResponse();
            $response->setData(array('error'=> 'error'));
            return $response;
        }
    }
}

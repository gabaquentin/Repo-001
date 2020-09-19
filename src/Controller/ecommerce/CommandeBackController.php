<?php

namespace App\Controller\ecommerce;

use App\Entity\Commandes;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\CommandesRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ProduitController
 * @Route("/back/ecommerce/commande")
 * @package App\Controller\ecommerce
 */
class CommandeBackController extends AbstractController
{
    /**
     * @Route("/", name="commande_back")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function Show_Orders(EntityManagerInterface $manager)
    {
        $tab_com=[];
        $user=$this->getUser()->getId();
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        $commande=$manager->getRepository(Commandes::class)->findBy([
            'client'=>$user
        ]);
        foreach ($commande as $com) {
            $livreur = "Aucun Livreur";
            if($com->getLivreur() != null){
                $livreur=$com->getLivreur()->getNom().' '.$com->getLivreur()->getPrenom();
            }
            $new_com = [
                'id' => $com->getId(),
                'numero' => $com->getNumero(),
                'payement' => $com->getModePaiement(),
                'statut' => $com->getStatut(),
                'mode_liv' => $com->getModeLivraison(),
                'livreur' => $livreur,
                'client' => $com->getClient()->getNom().' '.$com->getClient()->getPrenom() ,
            ];
            array_push($tab_com, $new_com);
        }
        return $this->render('backend/ecommerce/commande/show_all_commande.html.twig',[
            "orders" => json_encode($tab_com)
        ]);
    }

    /**
     * @Route("/detail/{id}", name="get_order_back")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ProduitRepository $rep
     * @return Response
     */
    public function detail_commande(Request $request,EntityManagerInterface $manager,ProduitRepository $rep)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tab_user=[];
        $produit=[];
        $produits= [];
        $id=$request->get('id');
        $order=$manager->getRepository(Commandes::class)->find($id);
        $panier = $order->getPanier();
        for ($i =0 ; $i<$panier['items'] ; $i++){
            $prod = $rep->find($panier['products'][$i]['id']);
            $user=$entityManager->getRepository(User::class)->findBy(['id'=> $prod->getClient()]);
            $proprietaire= $user[0];
            $produit =[
                'id' => $panier['products'][$i]['id'],
                'name' => $panier['products'][$i]['name'],
                'quantity' => $panier['products'][$i]['quantity'],
                'type' => $prod->getTypeTransaction(),
                'prixpromo' => $prod->getPrixPromo(),
                'prix' => $prod->getPrix(),
                'categorie' =>$prod->getCategorieProd()->getNomCategorie(),
                'images' => $panier['products'][$i]['images'][0]['url'],
                'proprietaire' => $proprietaire
            ];
            array_push($produits,$produit);
        }
        $orders= [
            'id' => $order->getId(),
            'numero' => $order->getNumero(),
            'datecom' => $order->getDateCom(),
            'date_liv' => $order->getDateLivraison(),
            'payement' => $order->getModePaiement(),
            'livraison' => $order->getModeLivraison(),
            'status' => $order->getStatut(),
            'client' => $order->getClient(),
            'livreur' => $order->getLivreur(),
            'info_liv' => $order->getInfoLivraison(),
            'produits' => $produits,
            'total' => $panier['total']
        ];
        return $this->render('backend/ecommerce/commande/detail_commande.html.twig',[
            'order' => $orders
        ]);
    }

    /**
 * @param EntityManagerInterface $manager
 * @param Request $request
 * @return JsonResponse
 * @throws \Exception
 * @Route("/add_date_liv", name="add_date_liv")
 */
    public function Add_date_Liv(EntityManagerInterface $manager,Request $request){
        $date_liv = new \DateTime($request->get("date_liv"));
        $newDate = new \DateTime('now');
        if($newDate < $date_liv) {
            $order =  $manager->getRepository(Commandes::class)->find($request->get('id'));
            $order->setDateLivraison($date_liv);
            $manager->persist($order);
            $manager->flush();
            return $this->json($date_liv,200);
        }
        return $this->json("Date incorrecte",400);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param CommandesRepository $com_repo
     * @param UserRepository $user_repo
     * @return JsonResponse
     * @throws \Exception
     * @Route("/livreur/search", name="search_liv")
     */
    public function Chercher_Livreur(EntityManagerInterface $manager,Request $request,CommandesRepository $com_repo,UserRepository $user_repo){
        $liv = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $text = $request->get('livreur');
        $data=[];
        $query = $user_repo->createQueryBuilder('us')
            ->where('us.nom LIKE :liv')
            ->orWhere('us.prenom LIKE :liv')
            ->setParameter('liv', '%'.$text.'%')
            ->getQuery();
        $users = $query->getResult();
        foreach ($users as $user) {
            $liv = $user;
            $one_liv = [
                'id' => $liv->getId(),
                'nom' => $liv->getNom(),
                'prenom' => $liv->getPrenom(),
            ];
            array_push($data,$one_liv);
        }
        return $this->json($data,200);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param CommandesRepository $com_repo
     * @param UserRepository $user_repo
     * @return JsonResponse
     * @throws \Exception
     * @Route("/livreur/add", name="add_liv")
     */
    public function Ajouterr_Livreur(EntityManagerInterface $manager,Request $request,CommandesRepository $com_repo,UserRepository $user_repo){
        $order = $manager->getRepository(Commandes::class)->find($request->get('id'));
        $entityManager = $this->getDoctrine()->getManager();
        $user=$entityManager->getRepository(User::class)->find($request->get('livreur'));
        $order->setLivreur($user);
        $manager->persist($order);
        $manager->flush();
        return $this->json("Cool",200);
    }
}

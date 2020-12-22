<?php

namespace App\Controller\ecommerce;

use App\Entity\Commandes;
use App\Entity\Coupon;
use App\Entity\InfoLivraison;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeZoneToStringTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class CommandeFrontController
 * @Route("/ecommerce")
 * @package App\Controller\ecommerce
 */
class CommandeFrontController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function cart(EntityManagerInterface $manager,Request $request,Swift_Mailer $mailer)
    {
        /*
        $commande= $manager->getRepository(Commandes::class)->find(11);
        $payment_mode = $commande->getModePaiement();
        $cart=$commande->getPanier();
        $shipping = $commande->getModeLivraison();
        $numero = $commande->getNumero();
        $user = $commande->getClient();
        $message = (new \Swift_Message('Commande éffectué'))
            ->setFrom('thinkup237@gmail.com')
            ->setTo('dodomaurel123@gmail.com')
            ->setBody(
                $this->renderView('frontend/ecommerce/Commande/commande.html.twig',[
                    'payement' => $payment_mode,
                    'cart'=>$cart,
                    'shipping'=>$shipping,
                    'numero'=>$numero,
                    'firt_name'=>$user->getNom(),
                    'last_name'=>$user->getPrenom(),
                    'email'=>$user->getEmail()
                ]), 'text/html'
            );
        $mailer->send($message);
        $this->addFlash('message',"C'est bon");
        */
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('frontend/ecommerce/Commande/cart.html.twig');
    }

    /**
     * @Route("/checkout_step1", name="checkout_step")
     */
    public function checkout1()
    {
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('frontend/ecommerce/Commande/checkout_step1.html.twig');
    }

    /**
     * @Route("/checkout_step2             ", name="checkout_step2")
     */
    public function checkout2()
    {
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('frontend/ecommerce/Commande/checkout_step2.html.twig');
    }

    /**
     * @Route("/checkout_step3             ", name="checkout_step3")
     */
    public function checkout3()
    {
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('frontend/ecommerce/Commande/checkout_step3.html.twig');
    }

    /**
 * @Route("/checkout_step4", name="checkout_step4")
 */
    public function checkout4()
    {
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('frontend/ecommerce/Commande/checkout_step4.html.twig');
    }

    /**
     * @param EntityManagerInterface $manager
     * @Route("/order-list             ", name="order_list")
     * @return Response
     */
    public function order_list(EntityManagerInterface $manager)
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
            $new_com = [
                'id' => $com->getId(),
                'numero' => $com->getNumero(),
                'datecom' => $com->getDateCom(),
                'dateLiv' => $com->getDateLivraison(),
                'payement' => $com->getModePaiement(),
                'cart' => $com->getPanier(),
                'statut' => $com->getStatut(),
                'mode_liv' => $com->getModeLivraison(),
                'livreur' => $com->getLivreur(),
                'info_liv' => $com->getInfoLivraison(),
            ];
            array_push($tab_com, $new_com);
        }

        return $this->render('frontend/ecommerce/Commande/orders_list.html.twig',["orders"=>json_encode($tab_com)]);
    }


    /**
     * @param EntityManagerInterface $manager
     * @Route("/orders", name="order")
     * @return Response
     */
    public function Order(EntityManagerInterface $manager)
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
            $new_com = [
                'id' => $com->getId(),
                'numero' => $com->getNumero(),
                'datecom' => $com->getDateCom(),
                'dateLiv' => $com->getDateLivraison(),
                'payement' => $com->getModePaiement(),
                'cart' => $com->getPanier(),
                'statut' => $com->getStatut(),
                'mode_liv' => $com->getModeLivraison(),
                'livreur' => $com->getLivreur(),
                'info_liv' => $com->getInfoLivraison(),
            ];
            array_push($tab_com, $new_com);
        }

        return $this->render('frontend/ecommerce/Commande/Orders.html.twig',["orders"=>json_encode($tab_com)]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @Route("/single-order", name="single_order")
     * @return Response
     */
    public function Single_Order(EntityManagerInterface $manager)
    {
        if($this->getUser() == null){
            return  $this->redirectToRoute('app_login');
        }
        $tab_com=[];
        $user=$this->getUser()->getId();
        $commande=$manager->getRepository(Commandes::class)->findBy([
            'client'=>$user
        ]);
        $count=0;
        foreach ($commande as $com) {
            $new_com = [
                'id' => $com->getId(),
                'numero' => $com->getNumero(),
                'datecom' => $com->getDateCom(),
                'dateLiv' => $com->getDateLivraison(),
                'payement' => $com->getModePaiement(),
                'cart' => $com->getPanier(),
                'statut' => $com->getStatut(),
                'mode_liv' => $com->getModeLivraison(),
                'livreur' => $com->getLivreur(),
                'info_liv' => $com->getInfoLivraison(),
            ];
            array_push($tab_com, $new_com);
        }
        return $this->render('frontend/ecommerce/Commande/Order.html.twig',["orders"=>json_encode($tab_com)]);
    }


    //Commande aui permet de vérifier la validité d'un code coupon

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/chek_coupon", name="coupon")
     */
    public function Check_coupon(EntityManagerInterface $manager,Request $request)
    {
        $newDate = new \DateTime('now');
        $data =[];
        if(!$request->isXmlHttpRequest())
            die();
        $user=$manager->getRepository(User::class)->findOneBy([
           'email'=>$this->getUser()->getUsername()
        ]);
        $code=$request->get("coupon");
        $coupon=$manager->getRepository(Coupon::class)->findOneBy([
                'code'=>$code
            ]);
        if($coupon != null){
            if($newDate < $coupon->getDateValidity()){
                $data=[
                    "remise"=>$coupon->getRemise(),
                    "id"=>$coupon->getId()
                ];
                if($coupon->getType() == "multiple"){
                    $manager->persist($coupon);
                    $manager->flush();
                    return $this->json($data,200,[],[]);
                }
                else if($coupon->getEtat() == "off"){
                    $coupon->setEtat('on');
                    $manager->persist($coupon);
                    $manager->flush();
                    return $this->json($data,200,[],[]);
                }
            }
        }
        return $this->json($data,500);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return JsonResponse
     * @Route("/commande", name="add_commande")
     * @throws \Exception
     */
    public function AddCommande(EntityManagerInterface $manager,Request $request,Swift_Mailer $mailer){
        $commande = new Commandes();
        $payment_mode = $request->get("payment");
        $cart=$request->get("cart",[]);
        $Ship_adr=$request->get("shippingAdress",[]);
        $shipping = $request->get("shipping");
        $numero = $request->get("numero");
        $email=$request->get("user");
        $user = $manager->getRepository(User::class)->findOneBy(['email'=>$email]);
        $commande->setDateCom(new \DateTime('now'))
            ->setModeLivraison($shipping)
            ->setModePaiement($payment_mode)
            ->setNumero($numero)
            ->setClient($user)
            ->setPanier($cart)
            ->setInfoLivraison($Ship_adr)
            ->setPrixLivraison($request->get("prixL"))
            ->setRemise($request->get("remise"))
            ->setStatut("En cours de Livraison");
        $manager->persist($commande);
        $manager->flush();
        $data=[
            "payment"=>$payment_mode
        ];
        return $this->json($data,200,[],[]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/trie_commande", name="trie")
     */
    public function Trier_Commande(EntityManagerInterface $manager,Request $request)
    {
        $chaine=$request->get('critere');
        $user=$this->getUser()->getId();
        $commande=$manager->getRepository(Commandes::class)->findBy([
            'client'=>$user,
            'statut'=>$chaine
        ]);
        if ($chaine == 'Tout'){
            $commande=$manager->getRepository(Commandes::class)->findBy([
                'client'=>$user
            ]);
        }
        $data = [];
        foreach ($commande as $com) {
            $new_com = [
                'id' => $com->getId(),
                'numero' => $com->getNumero(),
                'datecom' => $com->getDateCom(),
                'dateLiv' => $com->getDateLivraison(),
                'payement' => $com->getModePaiement(),
                'cart' => $com->getPanier(),
                'statut' => $com->getStatut(),
                'mode_liv' => $com->getModeLivraison(),
                'livreur' => $com->getLivreur(),
            ];
            array_push($data, $new_com);
        }
        return $this->json($data,200,[],[]);
    }
}

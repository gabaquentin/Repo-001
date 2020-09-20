<?php

namespace App\Controller\securo;

use App\Entity\Billing;
use App\Entity\Service;
use App\Entity\Shipping;
use App\Entity\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/frontend/authenticator.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'authentication' => 'login']);
    }

    /**
     * @Route("/validatePassword/{password}", name="app_validate_password")
     * @throws Exception
     */
    public function validatePassword(UserPasswordEncoderInterface $passwordEncoder, $password): Response
    {
        $jsonData = array();
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($this->getUser());

            $dbpassword = $user->getPassword();

            if($passwordEncoder->isPasswordValid($user, $password))
            {
                $jsonData["infos"] = "ok";

                return new Response(json_encode($jsonData));
            }
            else
            {
                throw new Exception('Incorrect : '.$dbpassword.' ---- my password : '.$passwordEncoder->encodePassword($user, $password).' is valid : '.$passwordEncoder->isPasswordValid($user, $password));
            }
    }

    /**
     * @Route("/esa/{value}", name="app_esa")
     * @throws Exception
     */
    public function esa($value): Response
    {
        // esa === enable shipping address
        $jsonData = array();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($this->getUser());

        if($user)
        {
            $user->setEsa($value);
            $entityManager->flush();

            $jsonData["infos"] = "ok";

            return new Response(json_encode($jsonData));
        }
        else
        {
            throw new Exception('User not found ');
        }


    }

    /**
     * @Route("/account", name="app_account")
     */
    public function account()
    {
        return $this->render('security/frontend/account.html.twig');
    }

    /**
     * @Route("/account/whishlist", name="app_whishlist")
     */
    public function whishlist()
    {
        return $this->render('security/frontend/whishlist.html.twig');
    }

    /**
     * @Route("/account/cart", name="app_cart")
     */
    public function cart()
    {
        return $this->render('security/frontend/cart.html.twig');
    }

    /**
     * @Route("/account/orders", name="app_orders")
     */
    public function orders()
    {
        return $this->render('security/frontend/orders.html.twig');
    }

    /**
     * @Route("/account/security", name="app_security")
     */
    public function security()
    {
        return $this->render('security/frontend/security.html.twig');
    }

    /**
     * @Route("/account/editaccount", name="app_account_edit")
     */
    public function editaccount(Request $request)
    {
        if($request->isXMLHttpRequest()) {

            $jsonData = array();
            $nom = addslashes(trim($request->get('nom')));
            $prenom = addslashes(trim($request->get('prenom')));
            $local = addslashes(trim($request->get('local')));
            $profil = addslashes(trim($request->get('image')));

            $paysPaiement = addslashes(trim($request->get('paysPaiement')));
            $postePaiement = addslashes(trim($request->get('postePaiement')));
            $villePaiement = addslashes(trim($request->get('villePaiement')));
            $quartierPaiement = addslashes(trim($request->get('quartierPaiement')));
            $addressPaiement = addslashes(trim($request->get('addressPaiement')));

            $esa = addslashes(trim($request->get('esa')));
            $paysLivraison = addslashes(trim($request->get('paysLivraison')));
            $posteLivraison = addslashes(trim($request->get('posteLivraison')));
            $villeLivraison = addslashes(trim($request->get('villeLivraison')));
            $quartierLivraison = addslashes(trim($request->get('quartierLivraison')));
            $addressLivraison = addslashes(trim($request->get('addressLivraison')));

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($this->getUser());
            $shipping = $entityManager->getRepository(Shipping::class)->find($user->getLivraison());
            $billing = $entityManager->getRepository(Billing::class)->find($user->getPaiement());

            if (!$user) {
                $jsonData["infos"] = "Auccun utilisateur trouvé pour cette session";
            }
            else
            {
                // update user contact
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setImage($profil);
                $user->setLocal($local);
                $user->setEsa($esa);

                // update user billing address
                $billing->setAdresse($addressPaiement);
                $billing->setPays($paysPaiement);
                $billing->setVille($villePaiement);
                $billing->setQuartier($quartierPaiement);
                $billing->setCodepostal($postePaiement);

                // update user shipping address
                if($esa == 1)
                {
                    $shipping->setAdresse($addressLivraison);
                    $shipping->setPays($paysLivraison);
                    $shipping->setVille($villeLivraison);
                    $shipping->setQuartier($quartierLivraison);
                    $shipping->setCodepostal($posteLivraison);
                }


                $entityManager->flush();

                $jsonData["infos"] = "Pour l'instant cava";
            }

            return new Response(json_encode($jsonData));
        }
        else
        {
            return $this->render('security/frontend/accountedit.html.twig');
        }
    }

    /**
     * @Route("/account/partenariat", name="app_partenariat")
     */
    public function indexPartenariat(Request $request)
    {
        if($request->isXMLHttpRequest()) {

            $jsonData = array();
            $partenariat = addslashes(trim($request->get('partenariat')));

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($this->getUser());

            if (!$user) {
                $jsonData["infos"] = "Auccun utilisateur trouvé pour cette session";
            }
            else
            {
                if($partenariat == "boutique")
                {
                    $nom = addslashes(trim($request->get('nom')));
                    $desc = addslashes(trim($request->get('desc')));
                    $domaine = explode(",",addslashes(trim($request->get('domaine',null))));
                    $logo = addslashes(trim($request->get('logo')));

                    $user->setBoutique($nom);
                    $user->setDescription($desc);
                    $user->setDomaine($domaine);
                    $user->setLogo($logo);
                    $user->setPartenariat($partenariat);
                    $user->setRoles((array)'ROLE_PARTENAIRE');
                    $user->setDp(new \DateTime());
                    $user->setPs(0);
                    $entityManager->flush();
                }
                else if ($partenariat == "services")
                {
                    $cin = addslashes(trim($request->get('cin')));
                    $desc = addslashes(trim($request->get('desc')));
                    $domaine = explode(",",addslashes(trim($request->get('domaine',null))));

                    $user->setCin($cin);
                    $user->setDescription($desc);
                    $user->setDomaine($domaine);
                    $user->setPartenariat($partenariat);
                    $user->setRoles((array)'ROLE_PARTENAIRE');
                    $entityManager->flush();
                }


                $jsonData["infos"] = "Demande envoyé avec succés";
            }

            return new Response(json_encode($jsonData));
        }
        else
        {
            $entityManager = $this->getDoctrine()->getManager();
            $services = $entityManager->getRepository(Service::class)->findAll();
            return $this->render('security/frontend/partenariat.html.twig',['services'=>$services]);
        }
    }

    /**
     * @Route("/back/lock", name="app_lock")
     * @throws Exception
     */
    public function lock(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($request->isXMLHttpRequest()) {

            $jsonData = array();
            $password = addslashes(trim($request->get('password')));

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($this->getUser());
            $dbpassword = $user->getPassword();
            if (!$user) {
                $jsonData["infos"] = "Auccun utilisateur trouvé pour cette session";
            }
            else
            {

                if($passwordEncoder->isPasswordValid($user, $password))
                {
                    $jsonData["infos"] = "ok";

                    return new Response(json_encode($jsonData));
                }
                else
                {
                    $jsonData["infos"] = "NaN";
                    throw new Exception('Incorrect : '.$dbpassword.' ---- my password : '.$passwordEncoder->encodePassword($user, $password).' is valid : '.$passwordEncoder->isPasswordValid($user, $password));
                }
            }

            return new Response(json_encode($jsonData));
        }
        else
        {
            return $this->render('security/backend/lock.html.twig');
        }
    }

    /**
     * @Route("/back/security/users", name="app_users")
     */
    public function users()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('security/backend/users.html.twig',array("users"=>$users));
    }

    /**
     * @Route("/back/security/admins", name="app_admins")
     */
    public function admins()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $admins = $entityManager->getRepository(User::class)->findByRole("ROLE_ADMIN");
        return $this->render('security/backend/admins.html.twig',array("admins"=>$admins));
    }

    /**
     * @Route("/back/security/partners", name="app_partners")
     */
    public function partners()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $partners = $entityManager->getRepository(User::class)->findByRole("ROLE_PARTENAIRE");
        return $this->render('security/backend/partners.html.twig',array("partners"=>$partners));
    }

    /**
     * @Route("/back/security/promoteUser/{departement}/{id}", name="app_promote_user")
     * @throws Exception
     */
    public function promoteUser($departement,$id): Response
    {
        // esa === enable shipping address
        $jsonData = array();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if($user)
        {
            $user->setDepartement($departement);
            $user->setRoles((array)'ROLE_ADMIN');
            $entityManager->flush();

            $jsonData["infos"] = "ok";

            return new Response(json_encode($jsonData));
        }
        else
        {
            throw new Exception('User not found ');
        }


    }

    /**
     * @Route("/back/security/retroUser/{id}", name="app_retro_user")
     * @throws Exception
     */
    public function retroUser($id): Response
    {
        // esa === enable shipping address
        $jsonData = array();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if($user)
        {
            $user->setDepartement("");
            $user->setRoles((array)'ROLE_USER');
            $entityManager->flush();

            $jsonData["infos"] = "ok";

            return new Response(json_encode($jsonData));
        }
        else
        {
            throw new Exception('User not found ');
        }


    }

    /**
     * @Route("/back/security/validatePartner/{statut}/{id}", name="app_validate_partner")
     * @throws Exception
     */
    public function validatePartner($id,$statut): Response
    {
        // esa === enable shipping address
        $jsonData = array();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if($user)
        {
            if($statut == 1)
            {
                $user->setPs($statut);
            }
            else if($statut == 0)
            {
                $user->setPs($statut);
            }

            $entityManager->flush();

            $jsonData["infos"] = "ok";

            return new Response(json_encode($jsonData));
        }
        else
        {
            throw new Exception('User not found ');
        }


    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

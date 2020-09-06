<?php

namespace App\Controller\securo;

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
     * @Route("/account", name="app_account")
     */
    public function account()
    {
        return $this->render('security/frontend/account.html.twig');
    }

    /**
     * @Route("/whishlist", name="app_whishlist")
     */
    public function whishlist()
    {
        return $this->render('security/frontend/whishlist.html.twig');
    }

    /**
     * @Route("/cart", name="app_cart")
     */
    public function cart()
    {
        return $this->render('security/frontend/cart.html.twig');
    }

    /**
     * @Route("/orders", name="app_orders")
     */
    public function orders()
    {
        return $this->render('security/frontend/orders.html.twig');
    }

    /**
     * @Route("/editaccount", name="app_account_edit")
     */
    public function editaccount()
    {
        return $this->render('security/frontend/accountedit.html.twig');
    }

    /**
     * @Route("/partenariat", name="app_partenariat")
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
            return $this->render('security/frontend/partenariat.html.twig');
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

<?php

namespace App\Controller\securo;

use App\Entity\Billing;
use App\Entity\Shipping;
use App\Entity\User;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use App\Services\securo\RegistrationCheck;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register/{role}", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param AuthenticationUtils $authenticationUtils
     * @param RegistrationCheck $registrationCheck
     * @param SluggerInterface $slugger
     * @param $role
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, AuthenticationUtils $authenticationUtils, RegistrationCheck $registrationCheck ,SluggerInterface $slugger, $role): Response
    {
        if($request->isXMLHttpRequest()) {

            $jsonData = array();
            $nom = addslashes(trim($request->get('nom')));
            $prenom = addslashes(trim($request->get('prenom')));
            $email = addslashes(trim($request->get('email')));
            $tel = addslashes(trim($request->get('telephone')));
            $password = addslashes(trim($request->get('password')));
            $local = addslashes(trim($request->get('local')));
            $image = addslashes(trim($request->get('image')));

            // registration check
            $code = $registrationCheck->securityCheck($email,$tel);
            $jsonData["code"] = $code ;
            $jsonData["email"] = $email ;
            $jsonData["nom"] = $nom ;
            $jsonData["prenom"] = $prenom ;
            $jsonData["tel"] = $tel ;
            $jsonData["image"] = $image ;

            // code 0 : if no prblems with registration
            if($code == 0)
            {

                $user = new User();
                $shipping = new Shipping();
                $billing = new Billing();
                $entityManager = $this->getDoctrine()->getManager();

                // add empty shipping address

                $shipping->setAdresse("");
                $shipping->setPays("");
                $shipping->setVille("");
                $shipping->setQuartier("");
                $shipping->setCodepostal("");
                $entityManager->persist($shipping);
                $entityManager->flush();

                // add empty billing address

                $billing->setAdresse("");
                $billing->setPays("");
                $billing->setVille("");
                $billing->setQuartier("");
                $billing->setCodepostal("");
                $entityManager->persist($billing);
                $entityManager->flush();

                // add user
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail($email);
                $user->setTelephone($tel);
                $user->setLocal($local);
                $user->setLivraison($shipping);
                $user->setPaiement($billing);
                $user->setPhoneVerified(0);
                $user->setIsVerified(0);
                $user->setEsa(0);
                $user->setAl(0);
                $user->setImage($image);
                $user->setCreation(new \DateTime());
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $password
                    )
                );

                // set user role
                if($role == "new_user")
                    $user->setRoles((array)'ROLE_USER');
                else if($role == "new_admin")
                    $user->setRoles((array)'ROLE_ADMIN');

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($user);
                $entityManager->flush();


                // generate a signed url and email it to the user


                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('thinkup237@gmail.com', 'ThinkUp Register Mailer'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );


                // do anything else you need here, like send an email

                $jsonData["infos"] = "Inscription effectué avec succés" ;

/*
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
*/

            } // code 100 : if email already exist in data base
            elseif($code == 100)
            {
                $jsonData["infos"] = "Cet email est déja enregistré dans notre base de données" ;
            } // code 200 : if phone number already exist in data base
            elseif($code == 200)
            {
                $jsonData["infos"] = "Ce telephone est déja enregistré dans notre base de données" ;
            } // code 300 : if phone number and email already exist in data base
            elseif ($code == 300)
            {
                $jsonData["infos"] = "Ce telephone et cet email sont déja enregistrés dans notre base de données" ;
            } // code 400 : if phone number and/or email is empty
            elseif ($code == 400)
            {
                $jsonData["infos"] = "Veuillez remplir tous les champs" ;
            } // return json

            return new Response(json_encode($jsonData));
        }
        else
        {
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            if($role == "new_user")
            {
                return $this->render('security/frontend/authenticator.html.twig', [
                    'role'=>$role, 'last_username' => $lastUsername, 'authentication' => 'register'
                ]);
            }
            else if($role == "new_admin")
            {
                return $this->render('security/frontend/authenticator.html.twig', [
                    'role'=>$role, 'last_username' => $lastUsername, 'authentication' => 'register'
                ]);
            }
            else
                return $this->render('errorpages/404error.html.twig');
        }

    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function verifyUserEmail(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register',['role'=>'new_user']);
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app');
    }
}

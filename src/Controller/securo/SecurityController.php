<?php

namespace App\Controller\securo;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
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
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @throws HttpException
     */
    private function getJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        return $data;
    }
}
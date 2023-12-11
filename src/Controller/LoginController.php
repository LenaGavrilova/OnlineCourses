<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{

    /**
     * @Route("/login", name="user_login",methods={"POST","GET"})
     */

    public function login(AuthenticationUtils $authenticationUtils)
    {

        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return new Response($this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ])
        );
    }

    /**
     * @Route("/logout", name="logout",methods={"GET"})
     */
    public function logout()
    {
    }
}
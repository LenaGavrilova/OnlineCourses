<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Service\UserRegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="user_registration",methods={"POST","GET"})
     */

    public function register(Request $request, UserRegisterService $service): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $service->registerUser($user);

            return $this->redirectToRoute('home');
        }

        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

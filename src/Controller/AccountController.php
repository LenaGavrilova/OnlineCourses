<?php

namespace App\Controller;

use App\Service\AccountService;
use App\Service\LessonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController
{

    /**
     * @Route("/myAccount", name="my_account", methods={"GET"})
     */
    public function myAccount(AccountService $service)
    {
        if ($this->IsGranted("ROLE_USER")) {
            $userLogin = $this->getUser()->getUserIdentifier();
            $user = $service->showUser($userLogin);

            return $this->render('account.html.twig', [
                'user' => $user[0]
            ]);
        } else {
            return $this->redirectToRoute('user_login');
        }
    }

    /**
     * @Route("/update", name="update_user_data", methods={"POST"})
     */
    public function updateUser(Request $request, AccountService $service): Response
    {
        if ($this->IsGranted("ROLE_USER")) {
            $data = json_decode($request->getContent(), true);
            $field = $data['field'];
            $value = $data['value'];

            $user = $this->getUser(); // Получаем текущего пользователя

            $service->updateUser($field, $value, $user);

            return $this->json(['message' => 'Данные успешно обновлены']);
        } else {
            return $this->redirectToRoute('user_login');
        }
    }


    /**
     * @Route("/addFeedback", name="addFeedback", methods={"POST","GET"})
     */
    public function addComment(Request $request, AccountService $service, LessonService $lservice)
    {
        if ($this->IsGranted("ROLE_USER")) {
            $lessonId = $request->get('id');
            $lesson = $lservice->showLesson($lessonId);
            $userLogin = $this->getUser()->getUserIdentifier();
            $user = $service->showUser($userLogin);
            $text = $request->get('text');
            $service->addComment($lesson, $user[0], $text);

            return $this->redirectToRoute('lesson', ['lessonId' => $lessonId]);

        } else {
            return $this->redirectToRoute('user_login');
        }
    }

}


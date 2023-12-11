<?php

namespace App\Controller;

use App\Service\LessonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class LessonController extends AbstractController
{

    /**
     * @Route("/lesson/{lessonId}", name="lesson", methods={"POST","GET"})
     */
    public function lesson($lessonId, LessonService $service)
    {
        if ($this->IsGranted("ROLE_USER")) {

            $lesson = $service->showLesson($lessonId);

            $course = $lesson->getCourse();
            $courseId = $course->first()->getId();

            $count = $service->countLessons($courseId);

            $nextLessonId = $lessonId;

            if ($lessonId < $count) {
                $nextLessonId += 1;
            } else {
                $nextLessonId = null;
            }

            $comments = $service->getCommentsForLesson($lessonId);
            return $this->render('lesson.html.twig', [
                'lesson' => $lesson, 'course' => $course[0],
                'nextLessonId' => $nextLessonId,
                'comments' => $comments
            ]);
        } else {
            return $this->redirectToRoute('user_login');
        }
    }

}


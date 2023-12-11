<?php

namespace App\Controller;

use App\Service\CourseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CourseController extends AbstractController
{
    /**
     * @Route("/about", name="about",methods={"GET","POST"})
     */
    public function aboutCourse(Request $request, CourseService $service): Response
    {
        $courseId = $request->get('id');

        $course = $service->showCourse($courseId);
        return $this->render('about.html.twig', [
            'course' => $course
        ]);
    }


    /**
     * @Route("/lessons", name="addCourse", methods={"POST"})
     */
    public function startStudy(Request $request, CourseService $service)
    {
        if ($this->IsGranted("ROLE_USER")) {
            $courseId = $request->get('id');
            $course = $service->showCourse($courseId);

            $user = $this->getUser();

            $service->addInMyCourses($user, $course);

            $lessons = $course->getLessons();
            return $this->render('course.html.twig', [
                'course' => $course, 'lessons' => $lessons
            ]);
        } else {
            return $this->redirectToRoute('user_login');
        }
    }

    /**
     * @Route("/myCourses", name="my_courses", methods={"GET"})
     */
    public function myCourses(CourseService $service)
    {
        if ($this->IsGranted("ROLE_USER")) {
            $user = $this->getUser();

            $courses = $service->showCourses($user);

            $latestCourse = null;

            if (sizeof($courses) > 0) {
                $latestCourse = end($courses); // Получаем последний курс
            }
            return $this->render('userCourses.html.twig', [
                'courses' => $courses, 'lastCourse' => $latestCourse,
                'noCourse' => 'Вы не проходите никакой курс'
            ]);
        } else {
            return $this->redirectToRoute('user_login');
        }
    }
}

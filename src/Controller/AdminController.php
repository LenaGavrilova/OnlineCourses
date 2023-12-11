<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Form\CourseFormType;
use App\Form\UserRegistrationFormType;
use App\Service\AdminService;
use App\Service\UserRegisterService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_user_list", methods={"POST","GET"})
     */
    public function indexUsers(AdminService $service): Response
    {
        $users = $service->getAllUsers();

        return $this->render('admin/userlist.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/new", name="admin_user_new", methods = {"POST","GET"})
     */
    public function newUser(Request $request, UserRegisterService $service): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $service->registerUser($user);

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/newUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin_user_edit", methods = {"POST"})
     */
    public function editUser($id, AdminService $service, Request $request): Response
    {
        $user = $service->getUserById($id);

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userData = $form->getData();
            $service->editUser($userData, $user);

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/editUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/users/{id}/delete", name="admin_user_delete" , methods={"POST"})
     */
    public function deleteUser($id, AdminService $service): Response
    {

        $service->deliteUser($id);


        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/admin/feedbacks", name="admin_feedback_list", methods={"GET","POST"})
     */
    public function indexFeedbacks(AdminService $service): Response
    {
        $feedbacks = $service->getAllFeedbacks();

        return $this->render('admin/feedbackList.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }

    /**
     * @Route("/admin/feedbacks/{id}/delete", name="admin_feedback_delete" , methods={"POST"})
     */
    public function deleteFeedback($id, AdminService $service): Response
    {

        $service->deliteFeedback($id);


        return $this->redirectToRoute('admin_feedback_list');
    }

    /**
     * @Route("/admin/courses", name="admin_course_list", methods={"POST","GET"})
     */
    public function indexCourses(AdminService $service): Response
    {
        $courses = $service->getAllCourses();

        return $this->render('admin/courseList.html.twig', [
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/admin/courses/new", name="admin_course_new", methods = {"POST","GET"})
     */
    public function newCourse(Request $request, AdminService $service): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $course = $form->getData();
            $service->createCourse($course);

            return $this->redirectToRoute('admin_course_list');
        }

        return $this->render('admin/newCourse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/courses/{id}/edit", name="admin_course_edit", methods = {"POST"})
     */
    public function editCourse($id, AdminService $service, Request $request): Response
    {
        $course = $service->getCourseById($id);

        $form = $this->createFormBuilder($course)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('difficulty', TextType::class)
            ->add('category', TextType::class)
            ->add('image', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $courseData = $form->getData();
            $service->editCourse($courseData, $course);

            return $this->redirectToRoute('admin_course_list');
        }

        return $this->render('admin/editCourse.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/courses/{id}/delete", name="admin_course_delete" , methods={"POST"})
     */
    public function deleteCourse($id, AdminService $service): Response
    {

        $service->deliteCourse($id);


        return $this->redirectToRoute('admin_course_list');
    }

    /**
     * @Route("/admin/lessons", name="admin_lesson_list", methods={"POST","GET"})
     */
    public function indexLessons(AdminService $service): Response
    {
        $lessons = $service->getAllLessons();

        return $this->render('admin/lessonList.html.twig', [
            'lessons' => $lessons,
        ]);
    }

    /**
     * @Route("/admin/lessons/new", name="admin_lesson_new", methods = {"POST","GET"})
     */
    public function newLesson(Request $request, AdminService $service): Response
    {
        $courses = $service->getAllCourses();

        $courseChoices = [];
        foreach ($courses as $course) {
            $courseChoices[$course->getName()] = $course->getId();
        }

        $form = $this->createFormBuilder()
            ->add('course', ChoiceType::class, [
                'choices' => $courseChoices,
                'label' => 'Выберите курс',
            ])
            ->add('name', TextType::class, [
                'label' => 'Название урока',
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание урока',
            ])
            ->add('material', TextType::class, [
                'label' => 'Материал урока',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Длительность урока',
            ])
            ->add('save', SubmitType::class, ['label' => 'Создать'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $service->createLesson($data);

            return $this->redirectToRoute('admin_lesson_list');
        }

        return $this->render('admin/newLesson.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/lessons/{id}/edit", name="admin_lesson_edit", methods = {"POST"})
     */
    public function editLesson($id, AdminService $service, Request $request): Response
    {
        $lesson = $service->getLessonById($id);

        $courses = $service->getAllCourses();

        $courseChoices = [];
        foreach ($courses as $course) {
            $courseChoices[$course->getName()] = $course->getId();
        }
        $form = $this->createFormBuilder($lesson)
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Название урока',
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание урока',
            ])
            ->add('material', TextType::class, [
                'label' => 'Материал урока',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Длительность урока',
            ])
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lessonData = $form->getData();
            $service->editLesson($lessonData, $lesson);

            return $this->redirectToRoute('admin_lesson_list');
        }

        return $this->render('admin/editLesson.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/lessons/{id}/delete", name="admin_lesson_delete" , methods={"POST"})
     */
    public function deleteLesson($id, AdminService $service): Response
    {

        $service->deliteLesson($id);


        return $this->redirectToRoute('admin_lesson_list');
    }

}

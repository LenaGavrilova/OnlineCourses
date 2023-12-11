<?php

namespace App\Service;


use App\Entity\Course;
use App\Entity\Feedback;
use App\Entity\Lesson;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AdminService
{
    private $entityManager;

    private $validator;

    private $logger;


    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->logger = $logger;

    }


    public function getAllUsers()
    {
        try {
            $users = $this->entityManager->getRepository(User::class)->findAll();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $users;
    }

    public function deliteUser($id)
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->find($id);

            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->find($id);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $user;
    }

    public function editUser($userData, $user)
    {
        try {
            $user->setName($userData->getName());
            $user->setSurname($userData->getSurname());
            $user->setEmail($userData->getEmail());

            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function getAllFeedbacks()
    {
        try {
            $feedbacks = $this->entityManager->getRepository(Feedback::class)->findAll();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $feedbacks;
    }

    public function deliteFeedback($id)
    {
        try {
            $feedback = $this->entityManager->getRepository(Feedback::class)->find($id);

            $this->entityManager->remove($feedback);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function getAllCourses()
    {
        try {
            $courses = $this->entityManager->getRepository(Course::class)->findAll();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $courses;
    }

    public function deliteCourse($id)
    {
        try {
            $course = $this->entityManager->getRepository(Course::class)->find($id);

            $this->entityManager->remove($course);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function createCourse($courseData)
    {
        try {
            $course = new Course();

            $course->setName($courseData->getName());
            $course->setDescription($courseData->getDescription());
            $course->setDifficulty($courseData->getDifficulty());
            $course->setCategory($courseData->getCategory());
            $course->setImage($courseData->getImage());

            $errors = $this->validator->validate($course);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            } else {
                $this->entityManager->persist($course);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $course;
    }

    public function getCourseById($id)
    {
        try {
            $course = $this->entityManager->getRepository(Course::class)->find($id);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $course;
    }

    public function editCourse($courseData, $course)
    {
        try {
            $course->setName($courseData->getName());
            $course->setDescription($courseData->getDescription());
            $course->setDifficulty($courseData->getDifficulty());
            $course->setCategory($courseData->getCategory());
            $course->setImage($courseData->getImage());

            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function getAllLessons()
    {
        try {
            $lessons = $this->entityManager->getRepository(Lesson::class)->findAll();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $lessons;
    }

    public function deliteLesson($id)
    {
        try {
            $lesson = $this->entityManager->getRepository(Lesson::class)->find($id);

            $this->entityManager->remove($lesson);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function getLessonById($id)
    {
        try {
            $lesson = $this->entityManager->getRepository(Lesson::class)->find($id);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $lesson;
    }

    public function createLesson($data)
    {
        try {
            $lesson = new Lesson();
            $lesson->setName($data['name']);
            $lesson->setDescription($data['description']);
            $lesson->setMaterial($data['material']);
            $lesson->setDuration($data['duration']);

            $selectedCourse = $this->entityManager->getRepository(Course::class)->find($data['course']);
            if ($selectedCourse) {
                $lesson->addCourse($selectedCourse);
            }

            $this->entityManager->persist($lesson);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function editLesson($lessonData, $lesson)
    {
        try {
            $lesson->setName($lessonData->getName());
            $lesson->setDescription($lessonData->getDescription());
            $lesson->setDuration($lessonData->getDuration());
            $lesson->setMaterial($lessonData->getMaterial());
            $selectedCourse = $this->entityManager->getRepository(Course::class)->find($lessonData->getCourse()->first());
            if ($selectedCourse) {
                $lesson->addCourse($selectedCourse);
            }
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

}
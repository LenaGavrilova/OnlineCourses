<?php

namespace App\Service;


use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CourseService
{
    private $entityManager;

    private $validator;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->logger = $logger;

    }

    public function showCourse($id)
    {
        try {
            $course = $this->entityManager->getRepository(Course::class)->find($id);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $course;
    }

    public function addInMyCourses($user, $course)
    {
        try {
            $user->addCourse($course);

            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            } else {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $user;
    }

    public function showCourses($user)
    {
        try {
            $course = $this->entityManager->getRepository(Course::class)->findCoursesByUserLogin($user);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $course;
    }

}

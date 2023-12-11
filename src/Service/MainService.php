<?php

namespace App\Service;


use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class MainService
{
    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;

    }

    public function showCourses()
    {
        try {
            $courses = $this->entityManager->getRepository(Course::class)->findLatestCourses(9);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $courses;
    }

    public function showResult($name)
    {
        try {
            $result = $this->entityManager->getRepository(Course::class)->findByName($name);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $result;
    }

}
<?php

namespace App\Service;


use App\Entity\Course;

use App\Entity\Feedback;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;


class LessonService
{
    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;

    }


    public function showLesson($lessonId)
    {
        try {
            $lesson = $this->entityManager->getRepository(Lesson::class)->find($lessonId);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $lesson;
    }

    public function countLessons($courseId)
    {
        try {
            $count = $this->entityManager->getRepository(Course::class)->countLessonsForCourse($courseId);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $count;
    }

    public function getCommentsForLesson($lessonId): array
    {
        try {
            $comments = $this->entityManager->getRepository(Feedback::class)->findCommentsByLesson($lessonId);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $comments;
    }

}

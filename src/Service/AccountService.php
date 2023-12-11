<?php

namespace App\Service;


use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;


class AccountService
{
    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;

    }

    public function showUser($userLogin)
    {
        try {
            return $this->entityManager->getRepository(User::class)->findByLogin($userLogin);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());

        }
    }

    public function updateUser($field, $value, $user)
    {
        try {
            if ($field === 'name') {
                $user->setName($value);
            } elseif ($field === 'surname') {
                $user->setSurname($value);
            } elseif ($field === 'email') {
                $user->setEmail($value);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

    public function getCommentsForLesson($lessonId): array
    {
        try {
            $commetns = $this->entityManager->getRepository(Feedback::class)->findCommentsByLesson($lessonId);
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $commetns;
    }

    public function addComment($lesson, $user, $text)
    {
        try {
            $comment = new Feedback();
            $comment->addLessonId($lesson);
            $comment->addUserId($user);
            $comment->setText($text);
            $comment->setDate(date("d/m/Y , g:i a"));

            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
    }

}

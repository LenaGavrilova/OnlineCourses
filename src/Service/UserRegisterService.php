<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterService
{
    private $entityManager;
    private $repository;
    private $validator;
    private $hasher;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $repository,
                                ValidatorInterface     $validator, UserPasswordHasherInterface $hasher, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->validator = $validator;
        $this->hasher = $hasher;
        $this->logger = $logger;
    }

    public function registerUser($userData): User|Response
    {
        try {
            $user = new User();

            $user->setName($userData->getName());
            $user->setSurname($userData->getSurname());
            $user->setEmail($userData->getEmail());
            $user->setLogin($userData->getLogin());
            $password = $userData->getPassword();
            $hashedPassword = $this->hasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $date = date("d/m/Y");
            $user->setRegistrationDate($date);
            $user->setRoles((array)"ROLE_USER");


            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            }
            if (!$this->repository->findByLogin($userData->getLogin())) {

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return $user;
    }
}

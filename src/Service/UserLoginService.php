<?php

namespace App\Service;


use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

class UserLoginService
{

    private $repository;

    private $logger;

    public function __construct(UserRepository $repository, LoggerInterface $logger)
    {

        $this->repository = $repository;
        $this->logger = $logger;

    }

    public function loginUser($login, $password): bool
    {
        try {
            $user = $this->repository->findByLogin($login);
            if (!password_verify($password, $user[0]->getPassword())) {
                return false;
            }
        } catch (\Exception $exception) {
            $this->logger->error('Something went wrong: ' . $exception->getMessage());
        }
        return true;
    }
}
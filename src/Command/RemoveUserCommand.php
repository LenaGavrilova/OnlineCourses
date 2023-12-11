<?php

namespace App\Command;

// src/Command/DeleteUserCommand.php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveUserCommand extends Command
{
    protected static $defaultName = 'app:remove-user';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Deletes a user by ID')
            ->addArgument('userId', InputArgument::REQUIRED, 'User ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');

        if (!$userId) {
            $output->writeln('Please provide a valid user ID.');
            return Command::FAILURE;
        }


        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $output->writeln('User with ID ' . $userId . ' has been deleted.');
        } else {
            $output->writeln('User with ID ' . $userId . ' not found.');
        }

        return Command::SUCCESS;
    }
}


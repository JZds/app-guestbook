<?php

namespace App\UserBundle\Command;

use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommand extends Command
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, $name = null)
    {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('user:make-admin')
            ->addArgument('email', InputArgument::REQUIRED, 'Admin email')
            ->setDescription('Makes user admin')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByEmail($input->getArgument('email'));
        if ($user === null) {
            $output->writeln('User not found.');
            return;
        }

        $user->addRole(User::ROLE_ADMIN);
        $this->entityManager->flush();
        $output->writeln('Done.');
    }
}

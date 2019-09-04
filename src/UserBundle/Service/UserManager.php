<?php

namespace App\UserBundle\Service;

use App\UserBundle\Entity\User;
use App\UserBundle\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager
{
    private $entityManager;
    private $eventDispatcher;
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->validator = $validator;
    }

    public function createUser(array $userData): User
    {
        $user = (new User())
            ->setUsername($userData['username'])
            ->setEmail($userData['email'])
            ->setPassword(password_hash($userData['password'], PASSWORD_BCRYPT))
            ->setEnabled(true)
            ->setRoles([User::ROLE_USER])
        ;
        $violations = $this->validator->validate($user);
        if ($violations->count() > 0) {
            $errors = [];
            /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            throw new ValidationException('invalid_validation', $errors, 400);
        }
        $this->entityManager->persist($user);

        return $user;
    }
}

<?php

namespace App\GuestBookBundle\DataFixtures;

use App\GuestBookBundle\Entity\Comment;
use App\UserBundle\DataFixtures\UserFixtures;
use App\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $manager->persist((new Comment())
                ->setUser($user)
                ->setApproved($user->getId() % 2 == 0)
                ->setContent(
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit,' .
                    ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                )
            );
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}

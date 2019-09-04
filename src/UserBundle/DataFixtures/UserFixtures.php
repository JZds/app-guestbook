<?php

namespace App\UserBundle\DataFixtures;

use App\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $manager->persist((new User())
                ->setUsername('user' . $i)
                ->setEmail($i . 'mail@mail.com')
                ->setPassword(password_hash('asd', PASSWORD_BCRYPT))
                ->setEnabled(true)
                ->setRoles([User::ROLE_USER])
            );
        }

        $manager->flush();
    }
}

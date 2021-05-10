<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('admin1');
        $user1->setPassword('passe');
        $user1->setRoles(['ROLE_ADMIN']);

        $user2 = new User();
        $user2->setUsername('admin2');
        $user2->setPassword('passe');
        $user2->setRoles(['ROLE_ETUDIANT']);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();
    }
}

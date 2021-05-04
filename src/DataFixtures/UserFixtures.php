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
        $user1->setUsername('utilisateur1');
        $user1->setPassword('password');
        $user1->setRoles(['ROLE_ETUDIANT']);

        $user2 = new User();
        $user2->setUsername('utilisateur2');
        $user2->setPassword('password');
        $user2->setRoles(['ROLE_ADMIN']);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('utilisateur2');
        $user->setPassword('passe');

        $manager->persist($user);
        $manager->flush();
    }
}

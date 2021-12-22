<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $superadmin = new User();
        $superadmin->setUsername('administrateur1');
        $superadmin->setPassword('1administrateur');
        $superadmin->setRoles(["ROLE_ADMIN", "ROLE_SUPERADMIN"]);

        $manager->persist($superadmin);
        // $manager->flush();
    }
}

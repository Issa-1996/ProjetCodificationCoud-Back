<?php

namespace App\DataFixtures;

use App\Entity\Lit;
use App\Entity\Campus;
use App\Entity\Chambre;
use App\Entity\Pavillon;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class LogementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
    
        
        // $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['logement'];
    }
}

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
    //     $campus  = new Campus();
    //     $campus->setNom("GC");

    //     $pavillon = new Pavillon();
    //     $pavillon->setNom("A");
    //     $pavillon->setCampus($campus);

    //     $chambre = new Chambre();
    //     $chambre->SetNumero("1");
    //     $chambre->setNombrelit("3");
    //     $chambre->setPavillon($pavillon);

    //     $chambre2 = new Chambre();
    //     $chambre2->setNumero("2");
    //     $chambre2->setNombrelit("3");
    //     $chambre2->setPavillon($pavillon);

    //     for ($i=1; $i <= 3; $i++) { 
    //         $lit = new Lit();
    //         $lit->setNumero($chambre->getNumero().$chambre->getPavillon()->getNom().$i.$campus->getNom());
    //         $lit->setChambre($chambre);
    //         $manager->persist($lit);
    //     }

    //     for ($i=1; $i <= 3; $i++) { 
    //         $lit = new Lit();
    //         $lit->setNumero($chambre2->getNumero().$chambre->getPavillon()->getNom().$i.$campus->getNom());
    //         $lit->setChambre($chambre2);
    //         $manager->persist($lit);
    //     }
        
        // $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['logement'];
    }
}

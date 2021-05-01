<?php

namespace App\Repository;

use App\Entity\Pavillon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pavillon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pavillon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pavillon[]    findAll()
 * @method Pavillon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PavillonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pavillon::class);
    }

    // /**
    //  * @return Pavillon[] Returns an array of Pavillon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pavillon
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use DateTime;
use App\Entity\QuotaLit;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method QuotaLit|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuotaLit|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuotaLit[]    findAll()
 * @method QuotaLit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotaLitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuotaLit::class);
    }

    // /**
    //  * @return QuotaLit[] Returns an array of QuotaLit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuotaLit
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findOneByNiveauQuot($numLit, $nomNiv): ?QuotaLit
    {
        $annee = new DateTime;
        $this->annee = $annee->format('Y');
        return $this->createQueryBuilder('q')
            ->join('q.lits', 'lit')
            ->andWhere('lit.numero = :valLit')
            ->setParameter('valLit', $numLit)
            ->join('q.niveau', 'niveau')
            ->andWhere('niveau.nom = :valNiv')
            ->setParameter('valNiv', $nomNiv)
            ->andWhere('q.annee = :annee')
            ->setParameter('annee', $annee)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

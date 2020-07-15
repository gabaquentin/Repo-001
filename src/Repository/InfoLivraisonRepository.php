<?php

namespace App\Repository;

use App\Entity\InfoLivraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfoLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoLivraison[]    findAll()
 * @method InfoLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoLivraison::class);
    }

    // /**
    //  * @return InfoLivraison[] Returns an array of InfoLivraison objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfoLivraison
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

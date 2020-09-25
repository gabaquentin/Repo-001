<?php

namespace App\Repository;

use App\Entity\BoostedProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoostedProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoostedProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoostedProducts[]    findAll()
 * @method BoostedProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoostedProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoostedProducts::class);
    }

    // /**
    //  * @return BoostedProducts[] Returns an array of BoostedProducts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BoostedProducts
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

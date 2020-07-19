<?php

namespace App\Repository;

use App\Entity\ExtraInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtraInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtraInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtraInfo[]    findAll()
 * @method ExtraInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtraInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtraInfo::class);
    }

    // /**
    //  * @return ExtraInfo[] Returns an array of ExtraInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtraInfo
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\QuestionService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionService|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionService|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionService[]    findAll()
 * @method QuestionService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionService::class);
    }

    // /**
    //  * @return QuestionService[] Returns an array of QuestionService objects
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
    public function findOneBySomeField($value): ?QuestionService
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

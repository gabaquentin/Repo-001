<?php

namespace App\Repository;

use App\Entity\ReponseDemande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseDemande|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseDemande|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseDemande[]    findAll()
 * @method ReponseDemande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseDemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseDemande::class);
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
    public function findQuestions(){
        return $this->createQueryBuilder("q")
            ->getQuery()
            ->getArrayResult();
    }

}

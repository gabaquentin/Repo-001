<?php

namespace App\Repository;

use App\Entity\Populaire;
use App\Services\eservice\Tools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Populaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Populaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Populaire[]    findAll()
 * @method Populaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PopulaireRepository extends ServiceEntityRepository
{
    private $tools;
    public function __construct(ManagerRegistry $registry, Tools $tools)
    {
        parent::__construct($registry, Populaire::class);
        $this->tools = $tools;
    }

    public function dataTablePopulaire(Request $request)
    {
        $start = $request->get("start");
        $length = $request->get("length");
        $search = $request->get("search");
        $order = $request->get("order");

        $valueSearch = $search["value"];
        $columnOrder = $this->tools->getColumnName($order[0]["column"]);
        $dirOrder = $order[0]["dir"];

        $qb = $this->createQueryBuilder("p")
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->orderBy("p.".$columnOrder,$dirOrder)
        ;
        if($valueSearch!="")
        {
            $qb->andWhere('p.titre like :value');
            foreach ($this->tools->getColumnsName() as $col)
                $qb->orWhere("p.".$col." like :value");

            $qb->setParameter(":value",'%'.str_replace("%"," ",$valueSearch).'%');
        }

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Populaire[] Returns an array of Populaire objects
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
    public function findOneBySomeField($value): ?Populaire
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

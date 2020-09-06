<?php

namespace App\Repository;

use App\Entity\Commandes;
use App\Services\ecommerce\CommandeTools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Commandes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commandes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commandes[]    findAll()
 * @method Commandes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandesRepository extends ServiceEntityRepository
{
    private  $tools;
    public function __construct(ManagerRegistry $registry,CommandeTools $tools)
    {
        parent::__construct($registry, Commandes::class);
        $this->tools = $tools;
    }
    public function dataTableCommande(Request $request)
    {
        $start = $request->get("start");
        $length = $request->get("length");
        $search = $request->get("search");
        $order = $request->get("order");

        $valueSearch = $search["value"];
        $columnOrder = $this->tools->getColumnName($order[0]["column"]);
        $dirOrder = $order[0]["dir"];

        $qb = $this->createQueryBuilder("com")
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->orderBy("com.".$columnOrder,$dirOrder)
        ;
        if($valueSearch!="")
        {
            $qb->andWhere('com.nom like :value');
            foreach ($this->tools->getColumnsName() as $col)
                $qb->orWhere("com.".$col." like :value");

            $qb->setParameter(":value",'%'.str_replace("%"," ",$valueSearch).'%');
        }

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Commandes[] Returns an array of Commandes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commandes
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

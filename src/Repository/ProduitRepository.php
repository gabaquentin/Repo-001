<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Services\ecommerce\Tools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    private $tools;
    public function __construct(ManagerRegistry $registry,Tools $tools)
    {
        parent::__construct($registry, Produit::class);
        $this->tools = $tools;
    }

    public function dataTableProduits(Request $request)
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
            $qb->andWhere('p.nom like :value');
            foreach ($this->tools->getColumnsName() as $col)
                $qb->orWhere("p.".$col." like :value");

            $qb->setParameter(":value",'%'.str_replace("%"," ",$valueSearch).'%');
        }

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
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
    public function findOneBySomeField($value): ?Produit
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

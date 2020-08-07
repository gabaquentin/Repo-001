<?php

namespace App\Repository;

use App\Entity\Date;
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

    public function qbShowProductFront(Request $request)
    {
        $max = $request->get("max");
        $start = $request->get("start");
        $searchValue = $request->get("searchValue");
        $idCategories = $request->get("idCategories",[]);
        $order = $this->tools->getOrderColumnProd($request->get("order",""));

        $qb = $this->createQueryBuilder("p")
            ->innerJoin(Date::class,"d")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->andWhere("p.visiblite=:visibilite")
            ->setParameter(":visibilite",1)
            ->orderBy($order[2].".".$order[0],$order[1])

        ;
        if(!empty($idCategories))
        {
            $qb->andWhere($qb->expr()->in("p.categorieProd",$idCategories));
        }
        if($searchValue!="")
        {
            $qb->andWhere("p.nom like :value")->setParameter(":value","%".str_replace("%"," ",$searchValue)."%");
        }

        return $qb;
    }

    public function showProductsFront(Request $request)
    {
        return $this->qbShowProductFront($request)->getQuery()->getResult();
    }

    public function countProductsFront(Request $request)
    {
        return count($this->qbShowProductFront($request)
            ->setMaxResults(null)->setFirstResult(null)
            ->getQuery()->getResult());
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

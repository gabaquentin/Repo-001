<?php

namespace App\Repository;

use App\Entity\Caracteristiques;
use App\Entity\Date;
use App\Entity\Dimension;
use App\Entity\Produit;
use App\Services\ecommerce\Tools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
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

    /**
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getMaxPrice()
    {
        return $this->createQueryBuilder("p")
            ->select("max(p.prix*(1-p.prixPromo))")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * filtre des produits pour le backend
     *
     * @param Request $request
     * @return int|mixed|string
     */
    public function dataTableProduits(Request $request)
    {
        $start = $request->get("start",0);
        $length = $request->get("length",0);
        $search = $request->get("search","");
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

    /**
     * QueryBuilder pour les produits du front
     *
     * @param Request $request
     * @return QueryBuilder
     */
    public function qbShowProductFront(Request $request)
    {
        $max = $request->get("max");
        $start = $request->get("start");
        $searchValue = $request->get("searchValue");
        $idCategories = $request->get("idCategories",[]);
        $order = $this->tools->getOrderColumnProd($request->get("order",""));

        $ville = $request->get("ville","");
        $typeTrans = $request->get("typeTransaction","");
        $prix = $request->get("prix",0);
        $meuble = $request->get("meuble",0);
        $nbreChambres = $request->get("nbreChambres",0);
        $nbreSalleBain = $request->get("nbresalleBain",0);
        $longueur = $request->get("longueur",0);
        $largeur = $request->get("largeur",0);

        $qb = $this->createQueryBuilder("p")
            ->innerJoin(Date::class,"d","WITH","d.id=p.date")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->andWhere("p.visiblite=:visibilite")
            ->setParameter(":visibilite",1)
            ->orderBy($order[2].".".$order[0],$order[1])

        ;

        if($ville!="")$qb->andWhere("p.ville=:ville")->setParameter(":ville",$ville);

        if($typeTrans!="")$qb->andWhere("p.typeTransaction=:typeTrans")->setParameter(":typeTrans",$typeTrans);;

        if($prix>0)$qb->andWhere("(p.prix*(1-p.prixPromo))<=:prix")->setParameter(":prix",$prix);;

        if($meuble>0 )$qb->andWhere("p.meuble=:meuble")->setParameter(":meuble",$meuble);

        if($longueur>0 || $largeur>0)$qb->innerJoin(Dimension::class,"di","WITH","di.id=p.dimension");

        if($nbreSalleBain>0 || $nbreChambres>0)$qb->innerJoin(Caracteristiques::class,"c","WITH","c.id=p.caracteristique");

        if($nbreChambres>0)
            $qb->andWhere("c.nbreChambres <=:nbreChambres")
                ->setParameter(":nbreChambres",$nbreChambres)
            ;

        if($nbreSalleBain>0)
            $qb->andWhere("c.nbreSalleBain <=:nbreSalleBain")
                ->setParameter(":nbreSalleBain",$nbreSalleBain)
            ;

        if($longueur>0)
            $qb->andWhere("di.longueur <=:longueur")
                ->setParameter(":longueur",$longueur)
            ;

        if($largeur>0)
            $qb->andWhere("di.largeur <=:largeur")
                ->setParameter(":largeur",$largeur)
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

    /**
     * affichage des produits pour le front
     *
     * @param Request $request
     * @return int|mixed|string
     */
    public function showProductsFront(Request $request)
    {
        return $this->qbShowProductFront($request)->getQuery()->getResult();
    }

    /**
     * nombre totoal de produits qui sont affichés dans le front
     *
     * @param Request $request
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countProductsFront(Request $request)
    {
        return ($this->qbShowProductFront($request)
            ->select("count(p.id)")
            ->setMaxResults(null)->setFirstResult(null)
            ->getQuery()->getSingleScalarResult());
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

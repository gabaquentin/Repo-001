<?php

namespace App\Repository;

use App\Entity\Caracteristiques;
use App\Entity\CategorieProd;
use App\Entity\Date;
use App\Entity\Dimension;
use App\Entity\Produit;
use App\Entity\User;
use App\Services\ecommerce\Tools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\AST\Functions\DateDiffFunction;
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

    public function __construct(ManagerRegistry $registry, Tools $tools)
    {
        parent::__construct($registry, Produit::class);
        $this->tools = $tools;
    }

    /**
     * recupere le plus grand prix des produits valides
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getMaxPrice()
    {
        return $this->createQueryBuilder("p")
            ->select("max(p.prix*(1-(p.prixPromo/100)))")
            ->innerJoin(Date::class, "d", "WITH", "d.id=p.date")
            ->andWhere("DATE_DIFF(CURRENT_TIMESTAMP(),d.dateModification)<:maxJour")
            ->innerJoin(CategorieProd::class, "c", "WITH", "p.categorieProd=c.id")
            ->andWhere("c.categorieParent is not null")
            ->setParameter(":maxJour", $this->tools->getDayMaxProduct())
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
        $start = $request->get("start", 0);
        $length = $request->get("length", 0);
        $search = $request->get("search", "");
        $order = $request->get("order");

        $valueSearch = $search["value"];
        $columnOrder = $this->tools->getColumnName($order[0]["column"]);
        $dirOrder = $order[0]["dir"];

        $qb = $this->createQueryBuilder("p")
            ->setFirstResult($start)
            ->setMaxResults($length)
            ->orderBy("p." . $columnOrder, $dirOrder);

        if ($valueSearch != "") {
            $qb->andWhere('p.nom like :value');
            foreach ($this->tools->getColumnsName() as $col)
                $qb->orWhere("p." . $col . " like :value");

            $qb->setParameter(":value", '%' . str_replace("%", " ", $valueSearch) . '%');
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
        $idCategories = $request->get("idCategories", []);
        $order = $this->tools->getOrderColumnProd($request->get("order", ""));

        $ville = $request->get("ville", "");
        $typeTrans = $request->get("typeTransaction", "");
        $prix = $request->get("prix", 0);
        $meuble = $request->get("meuble", 0);
        $nbreChambres = $request->get("nbreChambres", 0);
        $nbreSalleBain = $request->get("nbresalleBain", 0);
        $longueur = $request->get("longueur", 0);
        $largeur = $request->get("largeur", 0);

        $qb = $this->createQueryBuilder("p")
            ->innerJoin(Date::class, "d", "WITH", "d.id=p.date")
            ->innerJoin(CategorieProd::class, "c", "WITH", "p.categorieProd=c.id")
            ->andWhere("c.categorieParent is not null")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->andWhere("p.visiblite=:visibilite")
            ->setParameter(":visibilite", 1)
            ->andWhere("DATE_DIFF(CURRENT_TIMESTAMP(),d.dateModification)<:maxJour")
            ->setParameter(":maxJour", $this->tools->getDayMaxProduct())
            ->orderBy($order[2] . "." . $order[0], $order[1]);

        if ($ville != "") $qb->andWhere("p.ville=:ville")->setParameter(":ville", $ville);

        if ($typeTrans != "") $qb->andWhere("p.typeTransaction=:typeTrans")->setParameter(":typeTrans", $typeTrans);;

        if ($prix > 0) $qb->andWhere("(p.prix*(1-p.prixPromo))<=:prix")->setParameter(":prix", $prix);;

        if ($meuble > 0) $qb->andWhere("p.meuble=:meuble")->setParameter(":meuble", $meuble);

        if ($longueur > 0 || $largeur > 0) $qb->innerJoin(Dimension::class, "di", "WITH", "di.id=p.dimension");

        if ($nbreSalleBain > 0 || $nbreChambres > 0) $qb->innerJoin(Caracteristiques::class, "c", "WITH", "c.id=p.caracteristique");

        if ($nbreChambres > 0)
            $qb->andWhere("c.nbreChambres <=:nbreChambres")
                ->setParameter(":nbreChambres", $nbreChambres);

        if ($nbreSalleBain > 0)
            $qb->andWhere("c.nbreSalleBain <=:nbreSalleBain")
                ->setParameter(":nbreSalleBain", $nbreSalleBain);

        if ($longueur > 0)
            $qb->andWhere("di.longueur <=:longueur")
                ->setParameter(":longueur", $longueur);

        if ($largeur > 0)
            $qb->andWhere("di.largeur <=:largeur")
                ->setParameter(":largeur", $largeur);

        if (!empty($idCategories)) {
            $qb->andWhere($qb->expr()->in("p.categorieProd", $idCategories));
        }
        if ($searchValue != "") {
            $qb->andWhere("p.nom like :value")->setParameter(":value", "%" . str_replace("%", " ", $searchValue) . "%");
        }

        return $qb;
    }

    /**
     * affichage les produits visible et donc la date d'expiration n'est pas encore atteinte pour le front par requête ajax
     *
     * @param Request $request
     * @return int|mixed|string
     */
    public function showProductsFront(Request $request)
    {
        return $this->qbShowProductFront($request)->getQuery()->getResult();
    }

    /**
     * nombre total de produits les produits visible et donc la date d'expiration n'est pas encore atteinte qui sont affichés dans le front par requête ajax
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

    /**
     * @param int|array|null $idCategory
     * @return QueryBuilder
     */
    public function qbProductsValid($idCategory)
    {
        $qb = $this->createQueryBuilder("p")
            ->innerJoin(Date::class, "d", "WITH", "d.id=p.date")
            ->andWhere("p.visiblite=:visibilite")
            ->setParameter(":visibilite", 1)
            ->innerJoin(CategorieProd::class, "c", "WITH", "p.categorieProd=c.id")
            ->orderBy("d.dateModification","desc")
            ->andWhere("c.categorieParent is not null")
            ->andWhere("DATE_DIFF(CURRENT_TIMESTAMP(),d.dateModification)<:maxJour")
            ->setParameter(":maxJour", $this->tools->getDayMaxProduct());

        if(!is_null($idCategory))
        {
            if (is_array($idCategory)) {
                $qb->andWhere($qb->expr()->in("p.categorieProd", $idCategory));
            } else {
                $qb->andWhere("p.categorieProd=:cat")
                    ->setParameter(":cat", $idCategory);
            }
        }

        return $qb;
    }

    /**
     * compte les produits visible et donc la date d'expiration n'est pas encore atteinte
     * @param int|array|null $idCategory
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countProductsFrontValid($idCategory)
    {
        return $this->qbProductsValid($idCategory)
            ->select("count(p.id)")
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * récupère les produits visible et donc la date d'expiration n'est pas encore atteinte
     * @param int|array|null $idCategory
     * @param null|mixed $exception
     * @param null|array $selectedProd
     * @return int|mixed|string
     * @todo decommenter setParameter de execption
     */
    public function FindValidProducts($idCategory, $exception=null,$selectedProd=null)
    {
        $qb = $this->qbProductsValid($idCategory);
        if($exception!=null){
            //->andWhere("p.id!=:id")
            //->setParameter(":id",$exception)
        }
        if($selectedProd!=null && is_array($selectedProd))
        {
            $qb->andWhere($qb->expr()->in("p.id", $selectedProd));
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int|array|null $idCategory
     * @param User $user
     * @return Produit[]
     */
    public function FindValidProductsForBoost($idCategory,User $user)
    {
        $qb = $this->qbProductsValid($idCategory);

            $qb->andWhere("p.Client=:idClient")
            ->setParameter(":idClient",$user->getId());

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

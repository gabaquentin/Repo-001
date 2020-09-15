<?php

namespace App\Repository;

use App\Entity\Demande;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    // /**
    //  * @return Demande[] Returns an array of Demande objects
    //  */
    public function findByUser(int $id_user)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.client = :val')
            ->setParameter('val', $id_user)
            ->getQuery()
            ->getArrayResult();
    }

    public function find_demande_join_service($categorie, $keyword, $id_user)
    {
        if ($categorie == "La Totalité" and $keyword = "vide") {
            return $this->createQueryBuilder('d')->select('s.nom', 'd.description', 'd.date', 'd.heure','s.img','d.id','s.id AS service','c.img AS categorie_image')
                ->innerJoin('d.service', 's', Join::WITH, 'd.service = s.id')
                ->innerJoin('s.CategorieService', "c", Join::WITH, 's.CategorieService=c.id')
                ->innerJoin('d.client', 'u', Join::WITH, 'd.client=u.id')
                ->Where("u.id = :user")
                ->setParameters(array(
                    'user' => $id_user,
                ))
                ->getQuery()
                ->getArrayResult();
        } else if ($categorie == "La Totalité" and $keyword != "vide") {
            return $this->createQueryBuilder('d')->select('s.nom', 'd.description', 'd.date', 'd.heure','s.img','d.id','s.id AS service','c.img AS categorie_image')
                ->innerJoin('d.service', 's', Join::WITH, 'd.service = s.id')
                ->innerJoin('s.CategorieService', "c", Join::WITH, 's.CategorieService=c.id')
                ->innerJoin('d.client', 'u', Join::WITH, 'd.client=u.id')
                ->where('d.localisation like :keyword')
                ->orWhere('d.heure like :keyword')
                ->orWhere('d.description like :keyword')
                ->orWhere('d.date like :keyword')
                ->orWhere('s.nom like :keyword')
                ->Where("u.id = :user")
                ->setParameters(array(
                    'keyword' => $keyword,
                    'user' => $id_user,

                ))->getQuery()
                ->getArrayResult();
        } else if ($keyword == "vide" and $categorie != "La Totalité") {
            return $this->createQueryBuilder('d')->select('s.nom', 'd.description', 'd.date', 'd.heure','s.img','d.id','s.id AS service','c.img AS categorie_image')
                ->innerJoin('d.service', 's', Join::WITH, 'd.service = s.id')
                ->innerJoin('s.CategorieService', "c", Join::WITH, 's.CategorieService=c.id')
                ->innerJoin('d.client', 'u', Join::WITH, 'd.client=u.id')
                ->where('c.nom = :categorie')
                ->andWhere("u.id = :user")
                ->setParameters(array(
                    'categorie' => $categorie,
                    'user' => $id_user,
                ))->getQuery()
                ->getArrayResult();
        } else {
            return $this->createQueryBuilder('d')->select('s.nom', 'd.description', 'd.date', 'd.heure','s.img','d.id','s.id AS service','c.img AS categorie_image')
                ->innerJoin('d.service', 's', Join::WITH, 'd.service = s.id')
                ->innerJoin('s.CategorieService', "c", Join::WITH, 's.CategorieService=c.id')
                ->innerJoin('d.client', 'u', Join::WITH, 'd.client=u.id')
                ->where('d.localisation like :keyword')
                ->orWhere('d.heure like :keyword')
                ->orWhere('d.description like :keyword')
                ->orWhere('d.date like :keyword')
                ->orWhere('s.nom like :keyword')
                ->andWhere('c.nom = :categorie')
                ->andWhere("u.id = :user")
                ->setParameters(array(
                    'keyword' => "%".$keyword."%",
                    'categorie' => $categorie,
                    'user' => $id_user,
                ))->getQuery()
                ->getArrayResult();
        }

    }

    /*
    public function findOneBySomeField($value): ?Demande
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

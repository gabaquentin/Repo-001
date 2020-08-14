<?php


namespace App\DataFixtures;


use App\Entity\Caracteristiques;
use App\Entity\CategorieProd;
use App\Entity\Date;
use App\Entity\Dimension;
use App\Entity\Produit;
use App\Entity\Ville;
use App\Services\ecommerce\Tools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    private $tools;
    function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     * @param ObjectManager $manager
     * @return mixed
     */
    public function load(ObjectManager $manager)
    {
        $f = Factory::create("fr_FR");

        $cats = [];
        $villes = [];
        $typeProduit = $this->tools->getTypeProduit();
        for ($i=0 ;$i<4;$i++)
        {
            $cat = (new CategorieProd())
                ->setNomCategorie($f->sentence(2))
                ->setOrdre($i+1)
                ->setTypeCategorie($typeProduit[["immobilier","fourniture"][$f->numberBetween(0,1)]])
                ->setQuantite(0)
                ;
            $manager->persist($cat);
            $cats[] = $cat;
        }

        for ($i=0;$i<10;$i++)
        {
            $ville = (new Ville())
                ->setVilles($f->city)
            ;
            $manager->persist($ville);
            $villes[] = $ville;
        }

        for ($i=0 ;$i<300;$i++)
        {
            $tab = [1,2,3,4,5,null];
            $images = [
                "maison au bord de la plage-5f2594ac380a3.jpeg",
                "maison au bord de la plage-5f2594ac3af3a.jpeg",
                "maison au bord de la plage-5f2594ac3b773.jpeg",
                "maison au bord de la plage-5f2594ac3bef9.jpeg"
            ];

            /** @var CategorieProd $catP */
            $catP = $cats[$f->numberBetween(0,3)];

            $date = (new Date())->setDateAjout($f->dateTime("now"))->setDateModification($f->dateTime("now"));
            $manager->persist($date);

            $dimension = (new Dimension())
                ->setHauteur($tab[$f->numberBetween(0,5)])
                ->setLargeur($tab[$f->numberBetween(0,5)])
                ->setLongueur($tab[$f->numberBetween(0,5)]);
            $manager->persist($dimension);

            $produit = (new Produit())
                ->setNom($f->sentence(4))
                ->setCategorieProd($catP)
                ->setDimension($dimension)
                ->setPrix($f->numberBetween(100,100000000))
                ->setPrixPromo([$f->numberBetween(0,100),0][$f->numberBetween(0,1)])
                ->setNbreConsultations($f->numberBetween(0,3000000))
                ->setPriorite($f->numberBetween(1,1000))
                ->setLocalisation([$f->address,null][$f->numberBetween(0,1)])
                ->setVille($villes[$f->numberBetween(0,9)])
                ->setDescription([$f->paragraph(),null][$f->numberBetween(0,1)])
                ->setImages($images)
                ->setTypeTransaction(["vente","location"][$f->numberBetween(0,1)])
                ->setDate($date)
            ;


            if($catP->getTypeCategorie()=="immobilier")
            {
                $pieces = (new Caracteristiques())
                    ->setNbreChambres($tab[$f->numberBetween(0,5)])
                    ->setNbreParking($tab[$f->numberBetween(0,5)])
                    ->setNbreSalleBain($tab[$f->numberBetween(0,5)])
                    ;
                $manager->persist($pieces);
                $produit->setCaracteristique($pieces)
                    ->setDureeSejour([$f->numberBetween(1,600),null][$f->numberBetween(0,1)])
                    ->setMeuble([true,false][$f->numberBetween(0,1)])
                ;
            }
            $manager->persist($produit);
        }

        $manager->flush();
        $produits = $manager->getRepository(Produit::class)->findAll();

        foreach ($produits as $produit) {
            $pAss = [];
            $nbr = $f->numberBetween(0,5);
            for ($i=0 ;$i<$nbr;$i++)
                $pAss[] = $produits[$f->numberBetween(0,count($produits)-1)]->getId();

            $produit->setProduitsAssocies($pAss);
            $manager->persist($produit);
        }
        $manager->flush();
    }
}
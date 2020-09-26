<?php


namespace App\DataFixtures;


use App\Entity\Caracteristiques;
use App\Entity\CategorieProd;
use App\Entity\Date;
use App\Entity\Dimension;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Ville;
use App\Services\ecommerce\PackTools;
use App\Services\ecommerce\Tools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProductFixtures extends Fixture
{
    private $tools;
    private $encoder;
    private $packTools;
    function __construct(Tools $tools,PackTools $packTools,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->tools = $tools;
        $this->encoder = $passwordEncoder;
        $this->packTools = $packTools;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $f = Factory::create("fr_FR");
        $nbPosts = 5;

        /** @var CategorieProd[] $cats */
        $cats = [];
        $villes = [];
        $users = [];
        $typeProduit = $this->tools->getTypeProduit();
        for ($i=0 ;$i<8;$i++)
        {
            $cat = (new CategorieProd())
                ->setNomCategorie($f->sentence(2))
                ->setOrdre($i+1)
                ->setTypeCategorie($typeProduit[["immobilier","fourniture"][$f->numberBetween(0,1)]])
                ->setQuantite(0)
                ->setUniteAnnonce($f->numberBetween(50,2000))
                ->setUniteBoost($f->numberBetween(50,2000))
                ;
            $manager->persist($cat);
            $cats[] = $cat;
        }

        for ($i=2 ;$i<count($cats);$i++)
        {
            $cats[$i]->setCategorieParent($cats[$f->numberBetween(0,1)]);
        }
        $n=5;
        $defaultPack = [
            [
                "id" => "0",
                "titre" => "Default Pack",
                "description" => "Ce pack vous deonne la possibilité de poste ".$n." annonces gratuitement",
                "blaz" => "/frontend/img/illustrations/smile.svg",
                "prixBase" => "0 F CFA",
                "postes" => [
                    "nbrPostes" => $n,
                    "categories" => [],
                ],
            ],
        ];

        for ($i=0;$i<100;$i++)
        {
            $user = (new User())
                ->setPackProduct($defaultPack)
                ->setNom($f->firstName())
                ->setPrenom($f->firstName())
                ->setEmail($f->email)
                ->setTelephone($f->phoneNumber)
                ->setEsa(0)
                ->setAl(0)
                ->setIsVerified(0)
                ->setPhoneVerified(0)
                ->setLocal(["fr","en"][$f->numberBetween(0,1)])
                ->setCreation(new \DateTime())
                ->setRoles((array)'ROLE_USER')
                ->setImage("/images/produits/maison au bord de la plage-5f2594ac3b773.jpeg")
                ->setLogo("/images/produits/maison au bord de la plage-5f2594ac3b773.jpeg")
            ;
            $user->setPassword($this->encoder->encodePassword(
                $user,
                "Esprit2020"
            ));
            $manager->persist($user);
            $users[] = $user;
        }
        //$user = $manager->getRepository(User::class)->findOneBy(['email'=>"tabouaf@gmail.com"]);
        //$users[] = $user;

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
            $catP = $cats[$f->numberBetween(2,count($cats)-1)];

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
                ->setClient((!empty($users))?$users[$f->numberBetween(0,count($users)-1)]:null)
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
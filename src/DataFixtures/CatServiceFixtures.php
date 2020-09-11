<?php

namespace App\DataFixtures;

use App\Entity\CategorieService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CatServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 50; $i++){
            $categorie = new CategorieService();
            $categorie->setNom($faker->words(5, true))
                ->setDescription($faker->sentences(3, true));
            $categorie->setImg((string)['Jardinage-5f2a6b9f5b954.jpeg', 'Menuiserie-5f2a746d1e5fb.png']);
            $manager->persist($categorie);
        }
        // $product = new Product();

        $manager->flush();
    }
}

<?php


namespace App\DataFixtures;


use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TagFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager){

    // creation de notre instance de Faker:
        $faker = \Faker\Factory::create('fr_FR');

         # Génération de 10 tags aléatoire
         for($i = 1; $i <= 10 ; $i++)
         {
             # Création d'un Tag aléatoire
             $tag = new Tag();
             $tag->setName($faker->word);
 
             # Sauvegarde dans la BDD
             $manager->persist($tag);
         }
 
         # Execution des requètes de sauvegarde.
         $manager->flush();



    }
}







?>
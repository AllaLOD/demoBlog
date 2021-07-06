<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    // boucle va tourner 10 fois car nous avons besoin 10 articles 
    // Pour pouvoir insérer des données dans la table SQL article, nous devons instancier son entité correspondante (Article),
    // Symfony se sert l'objet entité $article pour injecter les valeurs dans les requetes SQL

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 11; $i++)
        {
            $article = new Article;

// On fait appel aux setteurs de l'objet entité afin de renseigner les titres, les contenu, 
//les images et les dates des faux articles stockés en BDD:

            $article->setTitre("Titre de l'article $i")
                    ->setContenu("<p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Error dolores similique harum recusandae modi magni earum atque maxime quisquam. Sunt quasi dolore nobis illum. Ea nostrum unde iste at? Eligendi.</p>")
                    ->setImage("https://picsum.photos/600/600")
                    ->setDate(new \DateTime());
// persist() recupere les donnée c'est comme "prepare" il prepare la requete SQL  d'insertion,
//// persist() : méthode issue de la classe ObjectManager permettant de préaprer et de garder en méméoire les requetes d'insertion
// $data=$bdd->prepare("INSERT INTO article VALUES ('getTitre()', 'getContenu()' etc..."));
// Un manager (ObjectManager) en Symfony est un classe permettant, entre autre, 
//de manipuler les lignes de la BDD (INSERT, UPDATE, DELETE)

            $manager->persist($article);       
        } 
            // flush() : méthode issue de la classe ObjectManager permettant véritablement d'executer les requetes d'insertions en BDD

        $manager->flush();
    }

}

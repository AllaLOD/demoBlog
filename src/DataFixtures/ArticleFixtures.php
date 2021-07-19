<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    // boucle va tourner 10 fois car nous avons besoin 10 articles 
    // Pour pouvoir insérer des données dans la table SQL article, nous devons instancier son entité correspondante (Article),
    // Symfony se sert l'objet entité $article pour injecter les valeurs dans les requetes SQL

    public function load(ObjectManager $manager)
    {    // on importe la librairy Faker pour les fixture, pour creer fausses articles avec fausses noms , prenoms et ext
        $faker = \Faker\Factory::create('fr_FR');

        // creation de 3 category

        for($cat = 1; $cat <= 3; $cat++)
        {
            $category = new Category;

            $category->setTitre($faker->word)
                     ->setDescription($faker->paragraph());  // parametre sort de librairy "faker"

            $manager->persist($category);

            // cretion de 4 à 10 article par category :
            for($art = 1; $art <= mt_rand(4,10); $art++)
            {

                $contenu = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>'; // on fait extrere les paragraphs de array pour avoir un string


                $article = new Article;

                $article->setTitre($faker->sentence())
                        ->setContenu($contenu)
                        ->setImage($faker->imageURL(600,600))
                        ->setDate($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                
                $manager->persist($article);

                // creation de boucle de 4 à 10 commentaires(alleatoire) pour chaque articles:

                for($cmt = 1; $cmt <= mt_rand(4,10); $cmt++)
                {
                   // traitement de date :
                    
                    $now = new DateTime;
                    $interval = $now->diff($article->getDate()); // retourne un time entre la date de creation et aujourd'hui

                    $days = $interval->days; //retourn ele nombre de jours entre creation d'article et d'aujourdèhui

                    $minimum = "-$days days";  // le but est d'avoir des dates des commentaires entre la date de creation d'articles et d'aujourdèhui

                    // traitement des données :
                    $comment = new Comment;

                    $contenu = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>'; 

                    $comment->setAuteur($faker->name)
                            ->setCommentaire($contenu)
                            ->setDate($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();


    }

}

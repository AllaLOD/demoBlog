<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * 
     * @Route("/admin/articles", name="admin_articles")
     * @Route("/admin/article/{id}/remove", name="admin_article_remove")
     * 
     */

     public function adminArticles(EntityManagerInterface $manager, ArticleRepository $repoArticles, Article $artRemove = null): Response  // nous avons ajouté un new argument
     {



        // via le manager qui permet de manipuler la BDD (insert, upadte, delete etc...),
        //  on execute la méthode getClassMetadata() afin de selectionner les méta données
        //  (primary key ,not null, noms des champs etc..) d'une entité (donc d'une table SQL), 
        // pour selectionner le nom des champs/colonnes de la table grace à la méthode getFieldNames()
        // getColumnMeta()


        $colonnes = $manager->getClassMetadata(Article::class)->getFieldnames();  //on recupere les colonens de notre table "Articles"

        // dump($colonnes);

        $articles = $repoArticles->findAll();

        //  traitement de supprision :

        if($artRemove)
        {
            // SI la condition IF retourne TRUE, cela veut dire $artRemove contient les informations de l'article a supprimer en BDD, on entre dans le IF
// Avant de supprimer l'article en BDD, on stock son ID dans une variable afin de l'injecter dans le message de validation


            $id = $artRemove->getId();
// remove() : méthode issue de l'interface EntityManagerInterface permettant de formuler une requete SQL de suppression (DELETE) 
            // $pdo->prepare("DELETE FROM article WHERE id = $_GET[id]")


            $manager->remove($artRemove);

            // flush() execute véritablement la requete DELETE en BDD (execute())

            $manager->flush();



            $this->addFlash(
               'success',
               "L'article n° $id a été bien supprimé de BDD"
            );

            // on ajour redirection vers la page pour ne pas avoir des erreur

            return $this->redirectToRoute('admin_articles');


        }

        // dump($articles);

         return $this->render('admin/admin_articles.html.twig', [     // on ajoute l'arguments
             'colonnes' => $colonnes,
             'articles' => $articles
         ]);
     }
}

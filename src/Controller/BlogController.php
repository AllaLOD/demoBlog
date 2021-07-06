<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{


    /**
     *
     * 
     * @Route("/blog", name="blog")
     */
    public function blog(ArticleRepository $repoArticles): Response
    {
        // requete de selection BDD des articles (on trouve les methodes à "ArticleRepositiry.php" qui les contient )
    // classe "repository" permet uniquement de formuler et executer des requetes SQL
     //$repoArticles = $this->getDoctrine->getRepository(Article::class)  => nous avons mis en commentaire car nous avons injecté diréctoment dans
     // blog()

        $articles = $repoArticles->findAll(); // methode qui SELECT * FROM article + FETCHALL et on recuper toutes les articles de BDD

        // dump($articles);


        return $this->render('blog/blog.html.twig', [
            'articlesBDD' => $articles  // on transmet au template les articles selectionnés eb BDD afin de le s traiter avec la language twig

        ]);
    }

    /**
     *  Méthode permettant d'afficher l'ensemble de de la page d'accueil du blog
     * 
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Blog dédié aux voyages !!!',
            'age' => 25
        ]);
    }

    
    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(): Response
    {
        return $this->render('blog/create.html.twig');
    }





    /**
     * Mèthode pour afficher les détailles d'un article
     * 
     * @Route("/blog/{id}", name="blog_show")
     */ 

    //  dans le cas precis on peut tout simplifier car symfony comprend de quoi il sagie ,d'un article dans bdd
    public function show(Article $article) : Response
    {   // importation de la classe Repository grace à méthode de "BlogController.php"
        // on met tout en commentaire ,car nous avons fait injection :

        // $repoArticles = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repoArticles->find($id); // on recuper les détails de l'article grace à methode finde()

        // dump($article);
        return $this->render('blog/show.html.twig', [
            'articleBDD' => $article
        ]);
    }


}

<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function blog(): Response
    {
        // requete de selection BDD des articles

        $repoArticles = $this->getDoctrine()->getRepository(Article::class);
        dump($repoArticles);

        $articles = $repoArticles->findAll();

        dump($articles);


        return $this->render('blog/blog.html.twig', [
            'controller_name' => 'BlogController',
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
     * Mèthode pour afficher les détailles d'un article
     * 
     * @Route("/blog/12", name="blog_show")
     */ 
    public function show() : Response
    {
        return $this->render('blog/show.html.twig');
    }



}

<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        
        //$repoArticles = $this->getDoctrine->getRepository(Article::class)  => nous avons mis en commentaire car nous avons injecté diréctement dans
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

    //1) nous avons une methode "manuelle" pour voir la differance:

    /**
     * @Route("/blog/new_old", name="blog_create_old")
     */
    public function createOld(Request $request,EntityManagerInterface $manager): Response
    {
        // dump($request);

        // La classe Request permet de stocker et d'avoir accès aux données véhiculées par les superglobales
        // ($_POST, $_GET, $_COOKIE, $_FILES etc...)

        // la propriété $request->request permet de stocker et d'accéder aux données saisie dans le formulaire,
        // c'est à dire aux données de la superglobale $_POST

        // Si les données sont supérieurs à 0, donc si nous avons bien saisie des données dans le formulaire,
        //alors on entre dans la condition IF

        if ($request->request->count() > 0) {
            $article = new Article;

            // Si nous vouclons insérer des données dans la table SQL Article, nous devons instancier et remplir 
            //un objert issu de son entité correspondante (classe Article)
            // On renseigne tout les setteurs de l'objet avec les données saisie dans le formulaire 
            // request->request->get('titre') : permet d'atteindre la valeur du titre saisi dans le champ 'titre' du formulaire
            // Pour manipuler les lignes de la BDD (INSERT, UPDATE, DELETE), nous avons besoin d'un mamager (EntityManagerInterface) 
            
            $article->setTitre($request->request->get('titre'))
                    ->setContenu($request->request->get('contenu'))
                    ->setDate(new \DateTime())                      //"DateTime" est une classe prédefinie
                    ->setImage($request->request->get('image'));

                    // dump($article);
                    // $data = $bdd->prepare("INSERT INTO article VALUES ('$article->getTitre()', '$article->getContenu()')")
                    // persist() : méthode issue de l'interface EntityManagerInterface permettant de préparer et garder en mémmoire
                    // la requete d'insertion
                    $manager->persist($article);

                    // flush() : méthode issue de l'interface EntityManagerInterface permettant veritablement d'executer le requete
                    // d'insertion en BDD : $data->execute()
        
                    $manager->flush();

                    // Après l'insertion de l'article en BDD, nous redirigeons l'internaute vers le'affichage du détail de l'article, donc une autre route via la méthode redirectToRoute()
                   // Cette méthode attend 2 arguments 
                   // 1. La route 
                   // 2. le paramètre a transmettre dans la route, dans notre cas l'ID de l'article

                    return $this->redirectToRoute('blog_show',[
                        'id' => $article->getId()
                    ]);
            

        }

        return $this->render('blog/create.html.twig');
    }
    

    // 2) methode qui est plus utilisée pour creation de formulaire:

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function create(Article $article = null, Request $request, EntityManagerInterface $manager): Response
    {
        // Si la variable $article N'EST PAS (null), si elle ne contient aucun article de la BDD, 
        //cela veut dire nous avons envoyé la route '/blog/new', c'est une insertion, on entre dans le IF et on crée une nouvelle instance
        // de l'entité Article, création d'un nouvel article.
        // Si la variable $article contient un article de la BDD, cela veut dire que nous avons envoyé la route '/blog/id/edit',
        // c'est une modifiction d'article, on entre pas dans le IF, $article ne sera pas null, il contient un article de la BDD, 
        //l'article à modifier


        if(!$article)                           
        {
            $article = new Article;
        }
       

        // $article->setTitre('titre bidone')  => les valeur sont envoyé direct au formulaire car il est lie avec entité
        //         ->setContenu("contenu bidon");

        // createForm() permet de creer un formulaire à partir de la classe ArticleType 
        // en 2 argument de createForm() nous transmetons l'objet entité $article afin de préciser que le formulaire a pour but 
        //de remplir l'objet $article

        $formArticle = $this->createForm(ArticleType::class, $article);

        // // handleRequest() permet ici dans notre cas, de récupérer toute les données saisie dans le formulaire et
        // de les transmettre aux bon setteurs de l'entité $article 
        // handleRequest() renseigne chaque setteur de l'entité $article avec les données saisi dans le formulaire


        $formArticle->handleRequest($request);

        if($formArticle->isSubmitted() && $formArticle->isValid())
        {
            // on appel setteur de date qui n'est pas rensegné dans le formulaire que dans le cas de creation de new articles:

            if (!$article->getId()) {
                $article->setDate(new \DateTime());
            }


         // persist() : méthode issue de l'interface EntityManagerInterface permettant de préparer et garder en mémmoire
         // la requete d'insertion
           $manager->persist($article);

         // flush() : méthode issue de l'interface EntityManagerInterface permettant veritablement d'executer le requete
         // d'insertion en BDD : $data->execute()
            $manager->flush();

            // après saisie nous avons redirection après insertion vers page blog_show
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);

            
        }



        return $this->render('blog/create2.html.twig', [
            'formArticle' => $formArticle->createView(),  // on transmet le formulaire au template afin de pouvoir l'afficher avec Twig 
            // createView() va retourner un petit objet qui représente l'affichage du formulaire,
            // on le récupère dans le template create2.html.twig
            'editMode' => $article->getId()  // on transmet l'id de l'article au template

        ]);


    }





    /**
     * Mèthode pour afficher les détailles d'un article
     *
     * @Route("/blog/{id}", name="blog_show")
     */

    //  dans le cas precis on peut tout simplifier car symfony comprend de quoi il sagie ,d'un article dans bdd
    public function show(Article $article, Request $request) : Response

    {   // importation de la classe Repository grace à méthode de "BlogController.php"
        // on met tout en commentaire ,car nous avons fait injection :

        // $repoArticles = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repoArticles->find($id); // on recuper les détails de l'article grace à methode finde()

        // dump($request);


        // on ajout traitement de commentaires d'article ( formulaire + insertion)

        $comment = new Comment;

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        dump($comment);
                      
        return $this->render('blog/show.html.twig', [
            'articleBDD' => $article,
            'formComment' => $formComment->createView()
        ]);
    }
}

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
     * Methode permettnt d'afficher la page d'accueil du blog
     * 
     * @Route("/blog", name="blog")
     */
    public function blog(ArticleRepository $repoArticles): Response
    {
        // Pour sélectionner des données dans la table SQL en BDD, nous devons importer la classe Repository qui correspon à la table SQL, c'est à dire à l'entité correspondante(Article)
        // une class repository permet uniquement de formuler et d'executer des requêtes SQL de selection(SELECT)
        // Cette classe contient des methods native de symfony mis à disposition pour formuler et executé des requêtes SQL en BDD
        //traitement requette selection BDD articles
        //$repoArticles est un objet issu de la classe ArticleRepository
        // $repoArticles = $this->getDoctrine()->getRepository(Article::class); lié a la ligne 20
        //getRepository() : method permettant d'importer la classe repository d'une entité.
        dump($repoArticles);// outil de debug propre à Symfony

        //findAll() : SELECT * FROM article + FETCHALL
        // $articles : tableau ARRAY multidimensionnel contenant l'ensemble des articles stockés dans la BDD
        $articles = $repoArticles->findAll();
        dump($articles); // outil de debug propre à Symfony

        return $this->render('blog/blog.html.twig', [
            'articlesBDD' => $articles // on transmet au template les articles que nous avons selectionnés en BDD afin de les traitées et de les afficher avec le langage TWIG
        ]);
    }

     /**
     * Methode permettnt d'afficher la page d'accueil du blog Symfony
     * 
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Blog dédié à la music, viendez voir, ça déchire !!!',
            'age' => 25
        ]);
    }

        /**
     * Method permettant d'afficher la page création du blog Symfony
     * 
     * @Route("/blog/new", name="blog_create")
     */
    public function create(): Response
    {
        return $this->render('blog/create.html.twig');
    }


      /**
     * Methode permettnt d'afficher le détail d'un article
     * 
     * @Route("/blog/{id}", name="blog_show")
     */
    // public function show(ArticleRepository $repoArticle, $id) : Response

    public function show(Article $article) : Response
    {   // l'ID transmit dans l'URL est envoyé directement en argument de la fonction show(), ce qui nous permet d'avoir acces à l'id de l'article a selectionner en BDD au sein de la methode.
        //dump($id);

        //Importation de la classe ArticleRepository
        // $repoArticle = $this->getDoctrine()->getRepository(Article::class); lié a la ligne 59
       // dump($repoArticle);

        //find() : methode mise à disposition par symfony issue de la classe ArticleRepository permettant de selectionner un élément de la BDD par son ID
        // $article : tableau ARRAY contenant toutes les données de l'article selectionné en BDD en fonction de l'ID transmit dans l'URL

        // SELECT $ FROM article WHERE id = 6 + FETCH
        //$article = $repoArticle->find($id);
        dump($article);

        return $this->render('blog/show.html.twig', [
            'articleBDD' => $article // on transmet au template les données de l'article selectionné en BDD afin de les traiter avec le langage dans le template.
        ]);
    }

}

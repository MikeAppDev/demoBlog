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
     * @Route("/blog/_old", name="blog_create_old")
     */
    public function createOld(Request $request, EntityManagerInterface $manager): Response
    {
        dump($request);

        // La classe Request permet de stocker et d'avoàir accès aux données véhiculées par les superglobales ($_POST, $_GET, $_COOKIE, $_FILES, etc...)
        // Si les données sont supérieurs à 0, donc si nous avons bien saisie des données dans le formulaire, alors on entre dans la condition IF

        if($request->request->count() > 0)
        {
            // Si nous vouclons insérer des données dans la table SQL Article, nous devons instancier et remplir un objert issu de son entité correspondante (classe Article)
            $article = new Article;

            // On renseigne tout les setteurs de l'objet avec les données saisie dans le formulaire 
            // request->request->get('titre') : permet d'atteindre la valeur du titre saisi dans le champ 'titre' du formulaire
            $article->setTitre($request->request->get('titre'))
                    ->setContenu($request->request->get('contenu'))
                    ->setImage($request->request->get('image'))
                    ->setDate(new \DateTime());

                    dump($article);

                    // Pour manipuler les lignes de la BDD (INSERT, UPDATE, DELETE), nous avons besoin d'un mamager (EntityManagerInterface) 
                    // persist() : méthode issue de l'interface EntityManagerInterface permettant de préparer et garder en mémmoire la requete d'insertion
                    // $data = $bdd->prepare("INSERT INTO article VALUES ('$article->getTitre()', '$article->getContenu()')")
                    $manager->persist($article);
                    
                    // flush() : méthode issue de l'interface EntityManagerInterface permettant veritablement d'executer le requete d'insertion en BDD
                    //Data -> execute
                    $manager->flush();

                    // Après l'insertion de l'article en BDD, nous redirigeons l'internaute vers l'affichage du détail de l'article, donc une autre route via la methode redirectToRoute()
                    //Cette methode attend 2 arguments
                    //1. La route
                    //2. le paramettre dans la route, dans notre cas l'ID de l'article
                    return $this->redirectToRoute('blog_show', [
                        'id' => $article->getID()
                    ]);
        }

        return $this->render('blog/create.html.twig');
    }

        /**
         * Methode permettnt d'afficher le détail d'un article
         * 
         * @Route("/blog/new", name="blog_create")
         * @Route("/blog/{id}/edit", name="blog_edit")
         */
        public function create(Article $article = null, Request $request, EntityManagerInterface $manager): Response
        {
            // Si la variable $article N'EST PAS (null), si elle ne contient aucun article de la BDD, cela veut dire nous avons envoyé la route '/blog/new', c'est une insertion, on entre dans le IF et on crée une nouvelle instance de l'entité Article, création d'un nouvel article
            // Si la variable $article contient un article de la BDD, cela veut dire que nous avons envoyé la route '/blog/id/edit', c'est une modifiction d'article, on entre pas dans le IF, $article ne sera pas null, il contient un article de la BDD, l'article à modifier
            if(!$article)
            {
                $article = new Article;
            }
            // en renseignant les setteur de l'entité, on s'aperçoit que les valeurs sont directement envoyé dans les attributs 'value' du formulaire, cela est du au fait que l'entité $article est relié au formulaire.
            // $article->setTitre("Titre bidon")
            //         ->setContenu("Contenu bidon");

            dump($request);

            // createForm() permet ici de créer un formulaire d'ajout d'article en fonction de la classe ArticleType.
            // En 2eme argument de createForm(), nous transmettons l'onjet entité $article afin de préciser que le formulaire a pour but de remplir l'onjet $article, on relie au formulaire.
            $formArticle = $this->createForm(ArticleType::class, $article);

            // handleRequest() permet ici dans notre cas, de récupérer toute les données saisie dans le formulaire et de les transmettre aux bon setteurs de l'entité $article 
            // handleRequest() renseigne chaque setteur de l'entité $article avec les données saisi dans le formulaire
            $formArticle->handleRequest($request);
            
            dump($article);

            // si le formulaire a bien été validé && que toutes les données saisie sont bien transmise à la bonne entité, alors on entre dans la condition IF
            if($formArticle->isSubmitted() && $formArticle->isValid())
            {
                // on renseigne le setteur de la date, puisque nous n'avons pas de champ 'date'dans le formulaire
                // Si l'article ne possède pas d'ID, alors on entre dans la condition IF et on execute le setteur de la date, on entre dans le IF que dans le cas de la création d'un nouvel article

                if(!$article->getId())
                {
                    $article->setDate(new \DateTime());
                }

                $manager->persist($article);
                $manager->flush();

                //redirection.
                return $this->redirectToRoute('blog_show', [
                    'id' => $article->getID()
                ]);
            }

            return $this->render('blog/create2.html.twig', [
                'formArticle' =>$formArticle->createView(), // on transmet le formulaire au template afin de pouvoir l'affichez avec Twig
                // createView() va rerouner un petit objet qui représente l'affichage du formulaire, on le récupère dans le template create2.html.twig
                'editMode' => $article->getId()
            ]);
        }


      /**
     * Methode permettnt d'afficher le détail d'un article
     * 
     * @Route("/blog/{id}", name="blog_show")
     */
    // public function show(ArticleRepository $repoArticle, $id) : Response

    public function show(Article $article, Request $request) : Response
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

        //TRAITEMENT COMMENTAIRE ARTICLE
        $comment = new Comment;

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);
        
        dump($request);

        return $this->render('blog/show.html.twig', [
            'articleBDD' => $article, // on transmet au template les données de l'article selectionné en BDD afin de les traiter avec le langage dans le template.
            'formComment' => $formComment->createView()
        ]);
    }

}

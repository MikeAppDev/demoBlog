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
     * Methodes qui affiche la page d'accuiel du backoffice
     * 
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * Methodes qui affiche la gestion des articles dans le backoffice
     * 
     * @Route("/admin/articles", name="admin_articles")
     * @Route("/admin/article/{id}/remove", name="admin_article_remove")
     */
    public function adminArticles(EntityManagerInterface $manager, ArticleRepository $repoArticles, Article $artRemove =null): Response
    {

        dump($artRemove);
        $colonnes = $manager->getClassMetadata(Article::class)->getFieldNames();
        dump($colonnes);

        $articles = $repoArticles->findAll();
        dump($articles);

        //TRAITEMENT SUPPRESSION ARTICLE BDD
        //si la condition IF retourne TRUE, cela veut dire $artRemove contient les informations de l'article a supprimer en BDD, on entre dans le IF
        if($artRemove)
        {
            $id = $artRemove->getId();

            // remonve() : methode issue de l'interface EntityManagerInterface permettant de formuler une requete SQL de suppression (DELETE)
            //$pdo->prepare("DELETE FROM article WHERE id = $GET[id]")
            $manager->remove($artRemove);
            // flush() execute véritablement la requete DELETE en BDD (execute())
            $manager->flush();

            $this->addFlash('success', "L'article n°$id a été supprimé avec succès ! ");

            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/admin_articles.html.twig', [
            'colonnes' => $colonnes,
            'articles' => $articles
        ]);
    }

}

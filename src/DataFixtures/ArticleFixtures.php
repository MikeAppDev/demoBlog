<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //la boucle for tourne 11 fois car nous voulons creer 11 articles.
        for($i = 1; $i <= 11; $i++)
        {
            //Pour pouvoir insérer des données dans la table SQL article, nous devons instancier son entité corespondante (Article), Symfony se sert de l'objet entité $article pour injecter les valeurs dans les requetes SQL
            $article = new Article;


            // On fait appel aux setteurs de l'objet entité afin de renseigner les titres, les contenu, les images et les dates des faux articles stockés en BDD

            $article->setTitre("Titre de l'article $i")
                    ->setContenu("<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>")
                    ->setImage("https://picsum.photos/600/600")
                    ->setDate(new \DateTime());
            //Un Manager (ObjectManager) en symfony est une classe permettant, entre autre, de manipuler les lignes de la BDD (INSERT, UPDATE, DELETE)

            //persist() : methode issu de la classe Object Manager permettant de préparer et de farder en mémoire les requetes d'insertion en BDD
            // $data = $bdd->prepare("INSERT INTO article VALUES ('getTitre()', 'getContenu()')")
            $manager->persist($article);
        }
        // flush() : méthode issue de la classe ObjectManager permettant véritablement d'executer les requetes d'insertions en BDD

        $manager->flush();
    }
}

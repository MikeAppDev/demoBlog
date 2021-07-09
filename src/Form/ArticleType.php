<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //la fonction add permet de créer les champs du formulaire
        $builder
            ->add('titre', TextType::class, [
                'required' => false,
                'label' => "coté ArticleType",
                'attr' => [ 
                    'placeholder' => "Le Titre Gros !"
                    
                ]
            ])
            //On définit le champ qui permet d'associer une catégorie à l'article dans le formulaire
            // Ce champ provient d'une autre entité : Category
            ->add('category', EntityType::class, [
                'class' => Category::class, // on précise de quelle entité provient ce champ
                'choice_label' => 'titre' // le contenu de la liste déroulante sera le titre des catégorues.
            ])
            ->add('contenu',TextareaType::class, [
                'required' => false,
                'label' => "Contenu créé coté ArticleType",
                'attr' => [ 
                    'placeholder' => "Contenu de l'article !",
                    'rows' => 6,
                    
                ]
            ])
            ->add('image',TextType::class,[
                'required' => false,
                'label' => "image créé coté ArticleType",
                'attr' => [ 
                    'placeholder' => "Une URL abuse pas..."
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

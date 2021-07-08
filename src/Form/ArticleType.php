<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label' => "coté ArticleType",
                'attr' => [ 
                    'placeholder' => "Le Titre Gros !"
                ]
            ])
            ->add('contenu',TextareaType::class, [
                'label' => "Contenu créé coté ArticleType",
                'attr' => [ 
                    'placeholder' => "Contenu de l'article !",
                    'rows' => 6,
                ]
            ])
            ->add('image',TextType::class,[
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

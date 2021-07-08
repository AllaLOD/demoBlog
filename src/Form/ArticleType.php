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
        // la function permet de creer champs de formulaire
        $builder
            ->add('titre', TextType::class, [
                'label' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Saisir le titre de l'article"
                ]
            ])
            ->add('contenu',TextareaType::class, [
                'label' => "Contenu d'article",
                'attr' => [
                    'placeholder' => "Saisir le contenu d'article",
                    'rows' => 10
                ]
            ])
            ->add('image', TextType::class, [
                'attr' => [
                    'placeholder' => "Saisir l'URL de l'image "
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

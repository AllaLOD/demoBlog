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
        // la function permet de creer champs de formulaire
        $builder
            ->add('titre', TextType::class, [
                'label' => "Titre de l'article",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le titre de l'article"
                    
                ]
            ])
        
                // Ce champ provient d'une autre entité : Category
                
            ->add('category', EntityType::class,[
                'class' => Category::class,     // on précise de quelle entité provient de ce champ
                'choice_label' => 'titre'        // le contenu de la liste déroulante sera le titre des catégories

            ])
            ->add('contenu', TextareaType::class, [
                'label' => "Contenu d'article",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le contenu d'article",
                    'rows' => 10
                ]
            ])
            ->add('image', TextType::class, [
                'required' =>false,
                'attr' => [
                    'placeholder' => "Saisir l'URL de l'image "
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

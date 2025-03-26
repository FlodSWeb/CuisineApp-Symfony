<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RecipeType extends AbstractType
{

    public function __construct(private FormListenerFactory $formListenerFactory)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => '',
                'label' => 'Nom de la recette'
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                // contraintes parametrees au niveau de l'entite Recipe.php
                // 'constraints' => new Sequentially([
                //         new Length(min: 4, max: 60, minMessage: 'Le slug doit contenir au moins 2 caractères.', maxMessage: 'Le slug doit contenir au plus 60 caractères.'),
                //         new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Le slug doit contenir uniquement des lettres minuscules, des chiffres et des tirets.')
                //         ])
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Image',
                'required' => false
                // Avec Vich, plus besoin de ces attributs
                // 'mapped' => false,
                // 'constraints' => [
                //     new Image()
                // ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de la recette'
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->timestamps())
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'validation_groups' => ['default', 'Extra']
        ]);
    }
}

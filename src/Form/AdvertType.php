<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AdvertType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre*',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('lodge', EntityType::class, [
                'class' => 'App\Entity\Lodge',
                'choice_label' => 'type',
                'label' => 'Type*',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix*',
                'required' => true,
                'scale' => 2,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'class' => 'input sm'
                ]
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code Postale*',
                'required' => true,
                'attr' => [
                    'class' => 'input sm'
                ]
              ])
            ->add('address', TextType::class, [
                'label' => 'Adresse*',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville*',
                'required' => true,
                'attr' => [
                    'class' => 'input sm'
                ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays*',
                'required' => true,
                'attr' => [
                    'class' => 'input sm'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description*',
                'required' => true,
                'attr' => [
                    'class' => 'textarea-description',
                ]
            ])
            ->add('other', TextareaType::class, [
                'label' => 'Autres détails',
                'required' => false,
                'attr' => [
                    'class' => 'textarea-description other'
                ]
            ])
            // Ajout d'un bouton de soumission avec le label 'Valider'
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'sub-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}

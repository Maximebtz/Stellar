<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class AdvertType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', FileType::class, [
                'label' => 'Images*',
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',  // Class for the input field
                ],
                'label_attr' => [
                    'class' => 'form-label',  // Class for the label
                ],
                'mapped' => false,
                // make it optional so you don't have to re-upload the avatar
                // every time you edit the user's details
                'required' => true,
            
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new All([
                        new File([
                            // 'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image document',
                        ])
                    ])
                ],
            ])
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
            ->add('accessory', EntityType::class, [
                'class' => 'App\Entity\Accessory',
                'choice_label' => 'name',
                'label' => 'Accessoires',
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

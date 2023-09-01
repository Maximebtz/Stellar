<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Accessory;
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class AdvertType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', FileType::class, [
                'multiple' => true,
                'label' => false,
                'attr' => [
                    'class' => 'image-input',  // Class for the input field
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
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Titre*'
                ],
                'label_attr' => [
                    'class' => 'label',  // Class for the label
                ],
            ])
            ->add('lodge', EntityType::class, [
                'class' => 'App\Entity\Lodge',
                'label' => false,
                'choice_label' => 'type',
                'required' => true,
                'placeholder' => 'Type *',
                'attr' => [
                    'class' => 'input',
                ],
                'label_attr' => [
                    'class' => 'label',  // Class for the label
                ],
            ])
            // ->add('accessory', EntityType::class, [
            //     'class' => Accessory::class,
            //     'choice_label' => 'name',
            //     'label' => 'Accessoires',
            // 'attr' => [
            //     'class' => 'form-check-input',
            //     'role' => 'switch',
            // ],
            //     'multiple' => true, // Permettre la sélection de plusieurs accessoires
            //     'expanded' => true, // Afficher les cases à cocher au lieu d'un select
            //     'required' => false, // Peut être facultatif
            // ])
            ->add('price', NumberType::class, [
                'label' => false,
                'required' => true,
                'scale' => 2,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Prix *',
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'label' => false,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Catégorie *',
                'attr' => [
                    'class' => 'input sm'
                ]
            ])
            ->add('cp', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'input sm',
                    'placeholder' => 'Code Postale*'
                ],
                'label_attr' => [
                    'class' => 'label',  // Class for the label
                ],
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Adresse *'
                ],
                'label_attr' => [
                    'class' => 'label',  // Class for the label
                ],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'input sm',
                    'placeholder' => 'Ville *'
                ],
                'label_attr' => [
                    'class' => 'label',  // Class for the label
                ],
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'input sm',
                    'placeholder' => 'Pays *'
                ],
                'label_attr' => [
                    'class' => 'label',  // Class for the label
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'textarea-description',
                    'placeholder' => 'Description *'
                ]
            ])
            ->add('other', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'textarea-description other',
                    'placeholder' => 'Autres spécificités'
                ]
            ])
            // Ajout d'un bouton de soumission avec le label 'Valider'
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'sub-btn'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('arrivalDate', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'name' => 'reservation[arrivalDate]',
                    'style' => 'display:none;'
                ],
            ])
            ->add('departureDate', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'name' => 'reservation[departureDate]',
                    'style' => 'display:none;'
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Samuel',
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'DeLaForet',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => '24 route du paradis',
                ]
            ])
            ->add('cp', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => '68000',
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Colmar',
                ]
            ])
            ->add('country', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'France',
                ]
            ])
            ->add('Reserver', SubmitType::class, [
                'attr' => [
                    'class' => 'next-btn'
                ]
            ]);

        $builder->add('advert', EntityType::class, [
            'class' => Advert::class,
            'choice_label' => 'id',
            'label' => false,
            'attr' => ['style' => 'display:none;'],
        ]);

        $builder->add('user_id', HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add('price', HiddenType::class, [
            'mapped' => false,
            'label' => false,
            'attr' => [
                'name' => 'reservation[price]',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

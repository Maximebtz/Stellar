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
                'widget' => 'single_text',
                'attr' => [
                    'name' => 'reservation[arrivalDate]',
                    'style' => 'display:none;'
                ],
            ])
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'name' => 'reservation[departureDate]',
                    'style' => 'display:none;'
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
            ])
            ->add('submit', SubmitType::class, [
                'label' => false,
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

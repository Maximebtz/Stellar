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
                'label' => 'Date d\'arrivée',
                'widget' => 'single_text',
            ])
            ->add('departureDate', DateType::class, [
                'label' => 'Date de départ',
                'widget' => 'single_text',
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
                'class' => Advert::class, // Remplacez par la classe de votre annonce
                'choice_label' => 'id', // Remplacez 'id' par le champ qui représente l'identifiant de l'annonce
                'label' => false, // Vous pouvez définir le libellé sur false pour masquer le label
                'attr' => ['style' => 'display:none;'], // Ajoutez ceci pour masquer le champ dans le formulaire
            ]);

            $builder->add('user_id', HiddenType::class, [
                'mapped' => false, // Assurez-vous que le champ ne soit pas mappé à l'entité Reservation
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'data_class' => Reservation::class,
        ]);
    }
}





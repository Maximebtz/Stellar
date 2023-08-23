<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'required' => true,
                'scale' => 2,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'label' => 'Category',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('other', TextareaType::class, [
                'label' => 'Other',
                'required' => false,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code Postale',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
              ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => true,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $user = $this->getUser(); // Obtenir l'utilisateur connecté

                // Vérifier si le user est connecté
                if ($user) {
                    $data['user'] = $user->getId(); // Associez l'ID du user qui fait l'annonce
                    $event->setData($data);
                }
            })
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

    private function getUser()
    {
        return $this->security->getUser();
    }
}

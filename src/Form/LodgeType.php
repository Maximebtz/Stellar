<?php

namespace App\Form;

use App\Entity\Lodge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LodgeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class)
            ->add('icon', FileType::class, [
                'label' => 'Icône',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ]),
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'sub-btn'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodge::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'attr' => [
                    'class' => 'form-control' // Appliquer la classe "form-control" au champ principal
                ],
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                // // instead of being set onto the object directly,
                // // this is read and encoded in the controller
                'mapped' => false,
            ])
            // ->add('avatar', FileType::class, [
            //     'label' => 'Photo de profil',
            //     'multiple' => true,
            //     'attr' => [
            //         'class' => 'form-control',  // Class for the input field
            //     ],
            //     'label_attr' => [
            //         'class' => 'form-label',  // Class for the label
            //     ],
            //     'mapped' => false,
            //     // make it optional so you don't have to re-upload the avatar
            //     // every time you edit the user's details
            //     'required' => false,
            
            //     // unmapped fields can't define their validation using annotations
            //     // in the associated entity, so you can use the PHP constraint classes
            //     'constraints' => [
            //         new All([
            //             new File([
            //                 // 'maxSize' => '1024k',
            //                 'mimeTypes' => [
            //                     'image/jpeg',
            //                     'image/jpg',
            //                     'image/png',
            //                     'image/webp',
            //                 ],
            //                 'mimeTypesMessage' => 'Please upload a valid image document',
            //             ])
            //         ])
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

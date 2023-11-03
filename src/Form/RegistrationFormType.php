<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Eo\HoneypotBundle\Form\Type\HoneypotType;
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
use Symfony\Component\Validator\Constraints\Regex;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('avatar', FileType::class, [
            //     'multiple' => false,
            //     'label' => "Photo de profil",
            //     'attr' => [
            //         'class' => 'image-input',
            //     ],
            //     'mapped' => false,
            //     'required' => false,
            //     'constraints' => [
            //         new All([
            //             new File([
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
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'attr' => [
                    'class' => 'input'
                ],
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'input']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                        'match' => true,
                        'message' => "Minimum 8 lettres, 1 lettre majuscule, 1 caractère spécial, 1 chiffre"
                    ])
                ],
            ])
            ->add('SOME-FAKE-NAME', HoneypotType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

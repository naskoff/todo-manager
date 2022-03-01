<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'Username'
                ],
                'row_attr' => [
                    'class' => 'form-floating mt-3'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Password',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mt-3'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => [
                        'placeholder' => 'Confirm password',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mt-3'
                    ]
                ]
            ])
            ->add('agree_terms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'By signing up you agree to our <a href="#">Terms & Conditions</a>',
                'label_html' => true,
                'row_attr' => [
                    'class' => 'mt-3'
                ],
                'label_attr' => [
                    'class' => 'checkbox-inline'
                ],
                'constraints' => [
                    new IsTrue(['message' => 'You must agree to our terms.'])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

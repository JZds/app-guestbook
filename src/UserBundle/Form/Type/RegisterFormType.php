<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                    'translation_domain' => 'UserBundle',
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'app_user.email',
                    ],
                ]
            )
            ->add('username', TextType::class, [
                'translation_domain' => 'UserBundle',
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'app_user.username',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'translation_domain' => 'UserBundle',
                'options' => [
                    'attr' => [
                        'class' => 'form-control',

                    ],
                ],
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'app_user.password',
                        'class' => 'form-control',
                        'autocomplete' => 'new-password'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'app_user.confirm_password',
                        'class' => 'form-control',
                        'autocomplete' => 'new-password'
                    ]
                ],
            ])
        ;
    }
}
<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'email.not_blank',
                    ]),
                ],
            ])->add('agreeTerms', CheckboxType::class, [
                'label'    => 'Accepter les termes et conditions',
                'mapped'   => false, // n'est pas mappé directement à une propriété de l'entité
                'required' => false,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mots-passe'],
                'second_options' => ['label' => 'Confirmé'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'password.not_blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'password.min_length',
                        'max' => 12,
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]+.*[0-9]+|[0-9]+.*[A-Z]+/',
                        'message' => 'password.digit_and_uppercase'
                    ]),
                ],
            ])
            // Disabling CSRF protection
            ->add('csrf_token', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Import pour le bouton Submit
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => ['placeholder' => 'Écrivez votre commentaire ici...'], // Attribut placeholder
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un commentaire',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Votre commentaire doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Poster le commentaire',
                'attr' => ['class' => 'btn btn-primary'], // Classe CSS pour le bouton
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

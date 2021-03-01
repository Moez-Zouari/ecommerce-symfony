<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])

            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon prénom'
            ])

            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon nom'
            ])

            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Vueillez saisir votre mot de passe actuel'
                ]
            ])

            /* Repeated Type : Qui nous permet de dire au symfony j'ai besoin
            de meme proprieté de generer 2 champs differents qui doivent
            avoir exactement le meme  contenu */
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
             /*  Mapped false :Pour indiquer a symfony que la propreité 
            que je te demande d'ajouter ici dans le formulaire
            tu ne dois pas l'a liée à mon entité car dans mon entité
            elle n'existe pas */
                'mapped' => false,
                'invalid_message' => 'Le mot de passe et la confirmation doivent étre identique',
                'label' => 'Mon nouveau mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                    ]
                ],

            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à  jour"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

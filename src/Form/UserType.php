<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=> 'input',
                    'placeholder'=> 'Écrivez le nom du membre'
                ]
            ])
            ->add('prenom',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=> 'input',
                    'placeholder'=> 'Écrivez le prénom du membre'
                ]
            ])
            ->add('email',EmailType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=> 'input',
                    'placeholder'=> 'Écrivez l\'adresse mail'
                ]
            ])
            ->add('roles', ChoiceType::class, ['choices' =>
                [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Client' => 'ROLE_CLIENT',
                ],
                'mapped'=>false,
                'label'=> false,
                'required'  => true,
                'multiple' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

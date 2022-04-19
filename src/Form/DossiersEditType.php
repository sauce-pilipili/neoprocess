<?php

namespace App\Form;

use App\Entity\Controls;
use App\Entity\Dossier;
use App\Entity\Dossiers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossiersEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('pieces',EntityType::class,[
                'class'=>Controls::class
            ])
        ;
        for ($i=0;$i< $options['attr'][0];$i++){
            $builder->add("piece_$i",FileType::class,[
                'label'=>false,
                'multiple'=>false,
                'mapped'=> false,
                'required'=> false,
                'attr'=>[
                    'class'=>'input'
                ]
            ])
                ->add("valid_$i",CheckboxType::class,[
                    'label'=>false,
                    'required'=> false,
                    'attr'=>[
                        'class'=>'input'
                    ]
                ]);
        }
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dossiers::class,
        ]);
    }
}

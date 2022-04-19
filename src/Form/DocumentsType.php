<?php

namespace App\Form;

use App\Entity\Documents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr'=>[
                    'class'=>'input',
                    'name'=>'text',
                    'id'=>'text'
                ]
            ])
            ->add('doc', ChoiceType::class, [
                'attr'=>[
                    'name'=>'select',
                    'id'=>'select'
                ],
                'mapped'=>false,
                'choices' => [
                    'selectionner une piece'=> "",
                    'piece rincipales' => [
                        'Carte identité' => 'Carte identité',
                        'Permis de conduier' => 'permis de conduire',
                    ],
                    'Pieces secondaires' => [
                        'facture' => 'facture',
                        'devis' => 'devis',
                    ],
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}

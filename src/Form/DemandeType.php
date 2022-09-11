<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Equipement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipement', EntityType::class, [
            'class'  => Equipement::class,
            'choice_label' => 'name'
        ])
            ->add('description')
            ->add('state',ChoiceType::class, [
                'choices'  => [
                    'En attente'=>'En attente',
                    'En cours' => 'En cours', 
                    'Soldé' =>  'Soldé'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}

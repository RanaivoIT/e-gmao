<?php

namespace App\Form;

use App\Entity\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('equipement')
            ->add('plannedAt')
            ->add('startedAt')
            ->add('finishedAt')
            ->add('comment')
            ->add('state',ChoiceType::class, [
                'choices'  => [
                    'En Attente'=>'En Attente',
                    'En cours' => 'En cours',
                    'Soldé' => 'Soldé'
                ]
            ])
            ->add('techniciens', EntityType::class, [
                'class'  => Tech::class,
                'choice_label' => 'nameAndSpeciality',
                'multiple' => true,'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}

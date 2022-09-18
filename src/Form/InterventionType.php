<?php

namespace App\Form;

use App\Entity\Equipement;
use App\Entity\Technicien;
use App\Entity\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',ChoiceType::class, [
                'choices'  => [
                    'CORRECTIVE'=>'CORRECTIVE',
                    'PREVENTIVE' => 'PREVENTIVE'
                ]
            ])
            ->add('equipement', EntityType::class, [
                'class'  => Equipement::class,
                'choice_label' => 'name'
            ])
            ->add('plannedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('startedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('finishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('comment')
            ->add('state',ChoiceType::class, [
                'choices'  => [
                    'En attente'=>'En attente',
                    'En cours' => 'En cours',
                    'Soldé' => 'Soldé'
                ]
            ])
            ->add('techniciens', EntityType::class, [
                'class'  => Technicien::class,
                'choice_label' => 'nameAndSpec',
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

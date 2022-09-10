<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Colection;
use App\Entity\Equipement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colection', EntityType::class, [
                'class'  => Colection::class,
                'choice_label' => 'name'
            ])
            ->add('site', EntityType::class, [
                'class'  => Site::class,
                'choice_label' => 'name'
            ])
            ->add('name')
            ->add('service')
            ->add('usedAt', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'format' => 'dd MMM yyyy'
            ])
            ->add('state',ChoiceType::class, [
                'choices'  => [
                    'En service'=>'En service',
                    'En panne'=>'En panne',
                    'Hors service' => 'Hors service'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipement::class,
        ]);
    }
}

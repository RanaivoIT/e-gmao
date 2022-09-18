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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colection', EntityType::class, [
                'class'  => Colection::class,
                'label' => 'Collection',
                'choice_label' => 'name'
            ])
            ->add('site', EntityType::class, [
                'class'  => Site::class,
                'choice_label' => 'name'
            ])
            ->add('service')
            ->add('name')
            ->add('reference')
            ->add('usedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
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

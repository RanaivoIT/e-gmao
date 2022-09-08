<?php

namespace App\Form;

use App\Entity\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('createdAt')
            ->add('plannedAt')
            ->add('startedAt')
            ->add('finishedAt')
            ->add('comment')
            ->add('state')
            ->add('equipement')
            ->add('techniciens')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Operateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('job')
            ->add('email')
            ->add('address')
            ->add('contact')
            ->add('password')
            ->add('picture')
            ->add('site')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operateur::class,
        ]);
    }
}

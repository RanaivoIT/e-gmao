<?php

namespace App\Form;

use App\Entity\Organe;
use App\Form\PieceType;
use App\Entity\Colection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrganeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colection', EntityType::class, [
                'class'  => Colection::class,
                'choice_label' => 'name'
            ])
            ->add('name')
            ->add('abbreviation')
            ->add('description')
            ->add(
                'pieces', 
                CollectionType::class, 
                [
                    'entry_type' => PieceType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organe::class,
        ]);
    }
}

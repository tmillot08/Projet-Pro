<?php

namespace App\Form;

use App\Entity\Jury;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationJuryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('fName')
            ->add('mail')
            ->add('type', EntityType::class, array(
                'class' => Type::class,

                'choice_label' => 'name',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    
        $resolver->setDefaults([
            'data_class' => Jury::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment')
            ->add('note', ChoiceType::class,array(
                'choices' =>array(
                    '0' => 1,
                    '0+' => 2,
                    '1-' => 3,
                    '1' => 4,
                    '1+' => 5,
                    '2-' => 6,
                    '2' => 7,
                    '2+' => 8,
                    '3-' => 9,
                    '3' => 10
                ),
                'multiple' => false,
                'expanded' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}

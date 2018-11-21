<?php

namespace App\Form;

use App\Entity\Folder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hero', TextareaType::class)
            ->add('hacks', TextareaType::class)
            ->add('why', TextareaType::class)
            ->add('nextYear', TextareaType::class)
            ->add('soloLink',UrlType::class)
            ->add('soloBadge',NumberType::class)
            ->add('codeLink', UrlType::class)
            ->add('codeBadge',NumberType::class)
            ->add('english',TextType::class)
            ->add('lastDiplome',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Folder::class,
        ]);
    }
}

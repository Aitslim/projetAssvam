<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom",
            ])
            ->add('description', TextType::class, [
                "label" => "Description",
            ])
            // ->add('createdAt')
            // ->add('modifiedAt')
            // ->add('user')
            ->add('budget')
            ->add('publiccible', TextType::class, [
                "label" => "Public cible",
            ])
            ->add('sponsor', TextType::class, [
                "label" => "Sponsor",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

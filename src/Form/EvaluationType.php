<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appreciation', TextType::class, [
                "label" => "Votre appreciation",
            ])
            ->add('note', TextType::class, [
                "label" => "Votre note",
            ])
            // ->add('user')
            ->add('project', EntityType::class, [
                'class' => Project::class,
                "label" => 'Projet',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);

    }
}

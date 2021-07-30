<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom",
                "disabled" => true,
            ])
            ->add('email', EmailType::class, [
                "label" => "Email",
                "disabled" => true,
            ])
            // ->add('roles')
            ->add('isVerified', CheckboxType::class, [
                "label" => "Email confirmé",
                "disabled" => true,
            ])
            ->add('IsSuspended', CheckboxType::class, [
                "label" => "Suspendre",
                "required" => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

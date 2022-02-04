<?php

namespace App\Form;

use App\Entity\Advertisement;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManageAccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('mail', EmailType::class)
            ->add('roles', ChoiceType::class, [
                "multiple"=> true,
                "choices" => [
                    "Admin" => User::$ROLE_ADMIN ,
                    "User" => User::$ROLE_USER,
                    "Editor" => User::$ROLE_EDITOR
                ]
            ])
            ->add('password', PasswordType::class, [
            ])
            ->add('Update', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

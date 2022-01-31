<?php

namespace App\Form;

use App\Entity\Advertisement;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdvertisementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('slider_pictures', FileType::class, array(
                'mapped' => false,
                'attr' => array(
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ),
                'multiple'=> "true"
            ))
            ->add('Create', SubmitType::class, array('label' => 'Insert Image', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')));/*           ->add('slider_pictures', FileType::class, [
                'label' => 'Slider pictures',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                'required' => false,
                'attr' => array(
                    'accept' => 'image/*',
                    'multiple' => 'multiple'
                ),
                'multiple' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please choose images',
                    ])
                ],

            ])*/;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}

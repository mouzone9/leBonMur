<?php

namespace App\Form;

use App\Entity\Advertisement;
use App\Entity\Tag;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('status', ChoiceType::class, [
                "choices" => [
                    "Draft" => Advertisement::$DRAFT_STATUS ,
                    "Public" => Advertisement::$PUBLIC_STATUS,
                    "Sold" => Advertisement::$SOLD_STATUS
                ]
            ])
            ->add("tags", EntityType::class, [
                "placeholder" => "Select tags",
                'class' => Tag::class,
                "multiple" => true,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder("tag");
                },
                "choice_label" => function(Tag $tag) {
                    return $tag->getName();
                }
            ])
            ->add('slider_pictures', FileType::class, array(
                'mapped' => false,
                'attr' => array(
                    'accept' => 'image/jpg image/png',
                    'multiple' => 'multiple'
                ),
                "required" => false,
                'multiple'=> true
            ))

            ->add('Create', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}

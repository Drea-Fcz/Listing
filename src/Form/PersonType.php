<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Person;
use App\Entity\Profile;
use App\Repository\HobbyRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'required' => false,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('job', EntityType::class, [
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'class' => Job::class,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('hobbies',EntityType::class, [
                'class' => Hobby::class,
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'query_builder' => function (HobbyRepository $hr) {
                    return $hr->createQueryBuilder('h')
                        ->orderBy('h.designation', 'ASC');
                },
                'choice_label' => 'designation',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image Profile',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                            'image/tiff',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])

            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}

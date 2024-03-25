<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username')
        ->add('email')
        ->add('dateOfBirth', DatePickerType::class)
        ->add('imageName')
        ->add('residence')
        ->add('rue')
        ->add('codepostal')
        ->add('ville')
        ->add('pays')
        ->add('job')
        ->add('hobby')
        ->add('numero')
        ->add('phone')
        ->add('firstname')
        ->add('lastname')
        ->add('imageFile', FileType::class, array('data_class' => null,
                'required'=>false
            ))
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

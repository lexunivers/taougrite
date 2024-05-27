<?php

namespace App\Form;

use App\Entity\ComptePilote;
use App\Entity\Qualification;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

     //   $reservataire = $options['reservataire'];

        $builder
            ->add('username')
        //    ->add('usernameCanonical')
            ->add('email')
        //    ->add('emailCanonical')
        //    ->add('enabled')
        //    ->add('salt')
        //    ->add('password')
        //    ->add('lastLogin', null, [
        //        'widget' => 'single_text',
        //    ])
        //    ->add('confirmationToken')
        //    ->add('passwordRequestedAt', null, [
        //        'widget' => 'single_text',
        //    ])
        //    ->add('roles')
        //    ->add('createdAt', null, [
        //        'widget' => 'single_text',
        //    ])
        //    ->add('updatedAt', null, [
        //        'widget' => 'single_text',
        //    ])
            ->add('residence')
            ->add('rue')
            ->add('ville')
            ->add('pays')
            ->add('job')
            ->add('hobby')
            ->add('numero')
            ->add('phone')
            ->add('firstname')
            ->add('codepostal')
            ->add('lastname')
            ->add('dateOfBirth', null, [
                'widget' => 'single_text',
            ])
        //    ->add('timezone')
        //    ->add('locale')
        //    ->add('resetToken')
        //    ->add('isVerified')
        //    ->add('updatedAt2', null, [
        //        'widget' => 'single_text',
        //    ])
            ->add('imageName')
        //    ->add('comptepilote', EntityType::class, [
        //        'class' => ComptePilote::class,
        //        'choice_label' => 'id',
        //    ])
            ->add('qualifications', EntityType::class, [
                'class' => Qualification::class,
                'choice_label' => 'name',
                'multiple' => true,
            //    'allow_add' => true,
            //    'allow_delete' => true,
                'by_reference' => false,
          //      'query_builder' => function(QualificationRepository $er) use ($reservataire) 
          //      {
          //          return $er->myfindQualifs($reservataire);
          //      }                
            ])

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

<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Symfony\Component\Form\Extension\Core\Type\EntityType;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Vol;
use Doctrine\ORM\EntityRepository;
use App\Repository\ReservationRepository ;

class VolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $reservataire = $options['reservataire'];

        $builder
        ->add('datevol', DateType::class, [
            'attr' => array(
            'placeholder' => 'Cliquez  pour le Calendrier',
            ),
            'years' => range(2020, 2030),
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'dd/MM/yyyy',              
            ])
            
        ->add('user', null, array('required' => false,'disabled' => true,))
        ->add('avion', null, array('placeholder' => 'Quel Appareil ?'))
        ->add('typevol', null, array('placeholder' => 'Choisissez !'))
        ->add('instructeur', null, array('placeholder' => 'Si Vol Ecole'))
        ->add('naturevol', null, array('placeholder' => 'Pour les Stats !'))
        //->remove('reservataire', EntityType::Class, ['class' => Reservation::class,'choice_label' => 'reservataire'])//, null, array('attr'=> array('placeholder' => 'Réservataire !') ))      
        //->add('CodeReservation', EntityType::class,['class'=>Reservation::class, 'choice_value' => 'NumeroOrdre', ])
       
         ->add('CodeReservation', EntityType::class, [                     
                        'label' => 'NumeroOrdre',
                        'class' => Reservation::class,
                        'choice_value' => 'NumeroOrdre',
                        'query_builder' => function(ReservationRepository $er) use ($reservataire) 
                        {
                            return $er->myfindCodeR($reservataire);
                        }
                    ])
        ->add('lieuDepart', null, array('placeholder' => 'Décollage de'))
        ->add('heureDepart')																
        ->add('lieuArrivee', null, array('placeholder' => 'Atterissage à'))
        ->add('heureArrivee')
        ->add('valider', SubmitType::class, array('label' => 'Valider'))                
        ;
   
    }



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Vol::class,
            'reservataire' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_entity_vol';
    }
}

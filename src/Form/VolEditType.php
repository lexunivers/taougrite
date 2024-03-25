<?php
// src/Sky/GestionVolBundle/Form/VolEditType.php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DatePickerType;

class VolEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operDate', null, array('required' => false,
                  'disabled'=> true,
                  'label' 	=> 'Date de Saisie',
            ))
            ->add('CodeReservation')
            ->add('facture')
            ->add('valider', SubmitType::class, array('label' => 'Confirmer'))
            ;
    }

    public function getParent()
    {
        return VolType::class;
    }
}

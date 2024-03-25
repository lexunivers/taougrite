<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;

final class OperationComptableAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('CompteId')
            ->add('OperDate')
            ->add('OperMontant')
            ->add('OperSensMt')
            ->add('Libelle')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('CompteId', null, ['label' => 'CompteId' ])
            ->add('user.comptepilote.id', null, array('label'=>'N° cptable'))
            ->add('user.comptepilote.nom', null, array('label'=>'Nom'))
            ->add('OperDate')
            ->add('OperMontant')
            ->add('OperSensMt')
            ->add('Libelle', FieldDescriptionInterface::TYPE_STRING, [
                'header_style' => 'width: 35%'
                ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],

            ]]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
           ->add('user', ModelListType::class, ['label'=>'Membre',
            'btn_add'       => 'AJouter un Membre',       //Specify a custom label
            'btn_list'      => 'liste des Pilotes',      //which will be translated
            'btn_delete'    => false,              //or hide the button.
            'btn_edit'      => 'Edit',             //Hide add and show edit button when value is set
            'btn_catalogue' => 'SonataUserUser', //Custom translation domain for buttons
                               ], ['placeholder' => 'Pas de Pilote selectionné',])
            ->add('OperDate', null,['label'=>'Date de l\'opération'])
            ->add('OperMontant', null,['label'=>'Montant'])
            ->add('OperSensMt', null,['label'=>'Pour Débit tapez 0 / Pour Crédit tapez 1'])
            ->add('Libelle')
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('CompteId', null, ['label' => 'Membre', 'value' => 'nom']) 
            ->add('OperDate')
            ->add('OperMontant')
            ->add('OperSensMt')
            ->add('Libelle')
            ;
    }
}

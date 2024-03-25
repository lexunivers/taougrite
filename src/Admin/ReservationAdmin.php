<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\TextType;

final class ReservationAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('start')
            ->add('end')
            ->add('title')
            ->add('resourceId')
            ->add('reservataire')
            ->add('instructeur')            
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('start')
            ->add('end')
            ->add('title')
            ->add('resourceId')
            ->add('reservataire')
            ->add('instructeur')           
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('start')
            ->add('end')
            ->add('title')
            ->add('resourceId')
            ->add('reservataire')          
            ->add('instructeur')

            ;        
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('start')
            ->add('end')
            ->add('title')
            ->add('resourceId')
            ->add('reservataire')
            ->add('instructeur')            
            ;
    }

    public function toString($object): string
    {
        return $object instanceof Reservation
            ? $object->getTitle()
            : 'Reservation Sélectionnée'; // shown in the breadcrumb on the create view
    }      
}

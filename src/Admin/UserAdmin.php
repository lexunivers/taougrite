<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Admin;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Sonata\Form\Type\DatePickerType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\UserBundle\Form\Type\SecurityRolesType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Form\Type\RolesMatrixType;
use Sonata\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Form\Type\ImageType;

/**
 * @phpstan-extends AbstractAdmin<UserInterface>
 */
class UserAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'user';

    /**
     * NEXT_MAJOR: Remove $userManager dependency and construct.
     */
    public function __construct(protected UserManagerInterface $userManager)
    {
        parent::__construct();
    }

    protected function configureFormOptions(array &$formOptions): void
    {
        $formOptions['validation_groups'] = ['Default'];

        if (!$this->hasSubject() || null === $this->getSubject()->getId()) {
            $formOptions['validation_groups'][] = 'Registration';
        } else {
            $formOptions['validation_groups'][] = 'Profile';
        }
    }


    protected function configureListFields(ListMapper $list): void
    {

        $list
            ->addIdentifier('username')
            ->add('id',TextType::class, array('label'=>'N° Pilote'))
            ->add('imageName', null, array('label'=>'photo'))					
            ->add('createdAt')
       ;

       if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
        $list
            ->add('impersonating', FieldDescriptionInterface::TYPE_STRING, [
                'virtual_field' => true,
                'template' => '@SonataUser/Admin/Field/impersonating.html.twig',
            ]);
    }

    $list->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
        'translation_domain' => 'SonataAdminBundle',
        'actions' => [
            'edit' => [],
            'show' => [],
            'delete' => [],
        ],
    ]);
}

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('username')
            ->add('email');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->tab('Fiche du Pilote', ['label'=>'Fiche du Pilote']) // the tab call is optional
                    ->with('Données Personnelles',['class' => 'col-md-3',
					        'box_class'   => 'box box-solid box-primary',
					        'label'=>'Données Personnelles'])
                        ->add('firstname')
                        ->add('lastname')
						->add('dateOfBirth', null, array('format'=>"d-m-Y"))
                        ->add('imageName', 'string', [
                            'template' => 'User/Profile/show_image.html.twig'])                                              
					->end()	
                    
                    ->with('Coordonnées',['class' => 'col-md-3',
                            'box_class'   => 'box box-solid box-primary',
                            'label'=>'Coordonnées'])					
						->add('residence', TextType::class, array( 'label'=>'Résidence','required' => false))
                        ->add('rue', TextType::class, array( 'label'=>'Rue', 'required' => false))
                        ->add('numero', TextType::class, array( 'label'=>'N°','required' => false))
                        ->add('ville', TextType::class, array( 'label'=>'Ville','required' => false))
                        ->add('codepostal', TextType::class, array( 'label'=>'Code Postal','required' => false))
                        ->add('pays', TextType::class, array( 'label'=>'Pays','required' => false))
                        ->add('phone', TextType::class, array( 'required' => false))
					->end()		
            			
                    ->with('Profile',['class' => 'col-md-3',
					        'box_class'   => 'box box-solid box-primary',
					        'label'=>'Profile'])					
                        ->add('locale')
                        ->add('timezone')
                        ->add('job', TextType::class, array( 'label'=>'Activité','required' => false))
                        ->add('id',TextType::class, array('label'=>'N° Pilote',)	)					
                        ->add('comptepilote.id', TextType::class, array('label'=>'N° Comptable',
                            'class' => User::class,
                            'associated_property'=>'id'                                                
                            ))
                    ->end()					

                    ->with('Identifiants',['class' => 'col-md-3',
					        'box_class'   => 'box box-solid box-primary',
					        'label'=>'Identifiants'])
                        ->add('username')
                        ->add('email')						
					->end()
					;				
    }

    protected function configureFormFields(FormMapper $form): void
    {		
        $form
            ->tab('Fiche du Pilote', ['label'=>'Fiche du Pilote']) // the tab call is optional
                ->with('Identité',['class' => 'col-md-4','box_class'   => 'box box-solid box-primary', 'label'=>'Identité'])
                    ->add('firstname', null, array('required' => false))
                    ->add('lastname', null, array('required' => false))

					// or DatePickerType if you don't need the time
					->add('dateOfBirth', DatePickerType::class)
                ->end()


			    ->with('Adresse',['class' => 'col-md-4','box_class'   => 'box box-solid box-primary', 'label'=>'Adresse'])
			
                    ->add('residence', TextType::class, array('label'=>'Résidence','required' => false))
                    ->add('rue', TextType::class, array('label'=>'Rue','required' => false))
                    ->add('numero', TextType::class, array('label'=>'N°','required' => false))
                    ->add('ville', TextType::class, array('label'=>'Ville','required' => false))
                    ->add('codepostal', TextType::class, array('label'=>'Code Postal','required' => false))
                    ->add('pays', TextType::class, array('label'=>'Pays','required' => false)) 
                ->end()
                ->with('Identité',['class' => 'col-md-4','box_class'   => 'box box-solid box-primary', 'label'=>'Identité'])
                    ->add('imageFile', FileType::class, array('label'=>'Photo à Télécharger ICI', 'data_class' => null,
                    'required'=>false))
                ->add('imageName', null, array('label'=>'Photo actuelle',
                    'sonata_admin' => 'Admin:show.html.twig')) //'AdminBundle:User:show_image.html.twig',
                 ->end()   
                ->with('Content', ['class' => 'col-md-4','box_class'   => 'box box-solid box-primary', 'label'=>'Divers'])
                    ->add('hobby', TextType::class, array('label'=>'Passe Temps','required' => false))
                    ->add('job', TextType::class, array('label'=>'Activité','required' => false))
                    ->add('locale', LocaleType::class, ['required' => false])
                    ->add('timezone', TimezoneType::class, ['required' => false])                    
                    
                ->end()
            ->end()

            ->tab('Données', ['label'=>'Données'])	
                ->with('Données', ['class' => 'col-md-4','box_class'   => 'box box-solid box-primary', 'label'=>'Identifiants'])
                    ->add('username')
                    ->add('email')
				    ->add('phone', TextType::class, array('required' => false))
                    ->add('plainPassword', TextType::class, [
                    'required' => (!$this->hasSubject() || null === $this->getSubject()->getId()),
                    ])					
                    ->add('plainPassword', TextType::class, [
                    'required' => (!$this->hasSubject() || null === $this->getSubject()->getId()),
                    ])				
                    ->add('enabled', null)
                ->end()
                ->with('roles', ['class' => 'col-md-4'])
                    ->add('realRoles', RolesMatrixType::class, [
                        'label' => false,
                        'multiple' => true,
                        'required' => false,
                    ])
                ->end();
    }

    protected function configureExportFields(): array
    {
        // Avoid sensitive properties to be exported.
        return array_filter(
            parent::configureExportFields(),
            static fn (string $v): bool => !\in_array($v, ['password', 'salt'], true)
        );
    }
}

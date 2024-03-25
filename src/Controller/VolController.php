<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\HiddenType;
use Doctrine\Common\Persistence\ObjectManager;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use App\Entity\Avions;
use App\Repository\AvionsRepository;
use App\Entity\Vol;
use App\Entity\Typevol;
use App\Form\VolType;
use App\Form\VolEditType;
use App\Entity\SonataUserUser;
use App\Entity\OperationComptable;
use App\Form\OperationComptableType;
use App\Form\OperationComptableEditType;
use Doctrine\Persistence\ManagerRegistry;

class VolController extends Controller
{
    public function __construct(private ManagerRegistry $doctrine) {} 

    #[Route('/vol', name: 'app_vol')]
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController', array('user'=>$reservataire)
        ]);
    }
    
    /**
     * This method can be overloaded in your custom CRUD controller.
     * It's called from createAction.
     *
     * @phpstan-param T $object
     */
    protected function preCreate(Request $request, object $object): ?Response
    {
        $newObject = $this->admin->getNewInstance();
        $this->admin->setSubject($newObject);
        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();
            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode($request) || $this->isPreviewApproved($request))) {
                /** @phpstan-var T $submittedObject */
                $submittedObject = $form->getData();				

                $operation = new OperationComptable();					
                $operation->setUser($newObject->getUser());
                $operation->setOperMontant($newObject->getMontantFacture());
                $operation->setOperSensMt(0);
                $operation->setLibelle($newObject->getLibelle());
                $operation->setCompteId($newObject->getUser()->getId() );
                
                // on renseigne l'attribut facture de l'entity Vol pour enregistrement en BDD
                $vol = new Vol(); 
                $form->getData()->setFacture($newObject->getMontantFacture());
				
               // On lie Vol à OperationComptable 
					$form->getData()->setComptable($operation);

                $this->admin->setSubject($submittedObject);
                try {
                    $newObject = $this->admin->create($submittedObject);					
                 
                    if ($this->isXmlHttpRequest($request)) {
                        return $this->handleXmlHttpRequestSuccessResponse($request, $newObject);
                    }
                    $this->addFlash(
                        'sonata_flash_success',
                        $this->trans(
                            'flash_create_success',
                            ['%name%' => $this->escapeHtml($this->admin->toString($newObject))],
                            'SonataAdminBundle'
                        )
                    );
                    // redirect to edit mode
                    return $this->redirectTo($request, $newObject);
                } catch (ModelManagerException $e) {
                    // NEXT_MAJOR: Remove this catch.
                    $this->handleModelManagerException($e);
                    $isFormValid = false;
                } catch (ModelManagerThrowable $e) {
                    $errorMessage = $this->handleModelManagerThrowable($e);
                    $isFormValid = false;
                }
            }
        }
		return null;		
	}   

    /**
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function preEdit(Request $request, object $object ): ?Response
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        $existingObject = $this->assertObjectExists($request, true);
        \assert(null !== $existingObject);

        $this->checkParentChildAssociation($request, $existingObject);
        $this->admin->checkAccess('edit', $existingObject);
        $this->admin->setSubject($existingObject);
        $objectId = $this->admin->getNormalizedIdentifier($existingObject);
        \assert(null !== $objectId);

        $form = $this->admin->getForm();
        $form->setData($existingObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();
			
            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode($request) || $this->isPreviewApproved($request))) {
               
                $submittedObject = $form->getData();

				$form->getData()->getComptable()->setUser($submittedObject->getUser());
				$form->getData()->getComptable()->setOperMontant($submittedObject->getMontantFacture());
				$form->getData()->getComptable()->setOperSensMt(0);								
				$form->getData()->getComptable()->setLibelle($submittedObject->getLibelle());
                $form->getData()->getComptable()->setCompteId($submittedObject->getUser()->getId() );

                // on renseigne l'attribut "facture" de l'entity Vol pour enregistrement en BDD
                $form->getData()->setFacture($submittedObject->getMontantFacture());                

                $this->admin->setSubject($submittedObject);

                try {
                    $existingObject = $this->admin->update($submittedObject);                                        
                    $em = $this->doctrine->getManager();
                    $em->flush();
                    if ($this->isXmlHttpRequest($request)) {
                        return $this->handleXmlHttpRequestSuccessResponse($request, $existingObject);
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->trans(
                            'flash_edit_success',
                            ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))],
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($request, $existingObject);
                } catch (ModelManagerException $e) {
                    // NEXT_MAJOR: Remove this catch.
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (ModelManagerThrowable $e) {
                    $errorMessage = $this->handleModelManagerThrowable($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', [
                        '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                        '%link_start%' => sprintf('<a href="%s">', $this->admin->generateObjectUrl('edit', $existingObject)),
                        '%link_end%' => '</a>',
                    ], 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if ($this->isXmlHttpRequest($request) && null !== ($response = $this->handleXmlHttpRequestErrorResponse($request, $form))) {
                    return $response;
                }

                $this->addFlash(
                    'sonata_flash_error',
                    $errorMessage ?? $this->trans(
                        'flash_edit_error',
                        ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))],
                        'SonataAdminBundle'
                    )
                );
            } elseif ($this->isPreviewRequested($request)) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        } 
        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

		return null;    
    }    
}

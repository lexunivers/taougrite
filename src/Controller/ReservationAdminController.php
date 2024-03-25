<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\InstructeurRepository;
use App\Entity\Avions;
use App\Entity\Vol;
use App\Entity\User;
use App\Repository\VolRepository;
use App\Repository\AvionsRepository;
use App\Entity\Resources;
use App\Entity\Instructeur;
use App\Form\InstructeurType;
use App\Form\ReservationEditType;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Persistence\ManagerRegistry;

class ReservationAdminController extends Controller
{

    public function __construct(private ManagerRegistry $doctrine) {}  

    #[Route('/reservation/admin', name: 'app_reservation_admin')]
    public function index(): Response
    {
        return $this->render('reservation_admin/index.html.twig', [
            'controller_name' => 'ReservationAdminController',
        ]);
    }

    /**
    * @Route("/prelist", name="reservation_list")
    */
    #[Route('/prelist', name:'reservation_list') ]
    public function preList(Request $request): ?Response
    {
         
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        // do your import logic

        $em = $this->doctrine->getManager();

		$form = $this->createForm(ReservationEditType::class);
        $form->add('user');
        $form->remove('start');
        $form->remove('end');
        $form->remove('title');
        $form->add('instructeur');
       
		$form->handleRequest($request);
		
		$form->getData();

			if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()){
			
				$user  = $form->getData()->getUser()->getId();
				$prenom = $form->getData()->getUser()->getFirstname();
				$nom = $form->getData()->getUser()->getLastname();
				$auteur  = $form->getData()->getUser()->getId();

                if ($instructeur = $form->getData()->getInstructeur() == "" ){ 
                    $instructeur = "";
                }else{                 
                $instructeur = $form->getData()->getInstructeur()->getNom();
                $instructeur = $form->getData()->getInstructeur()->getId();
                $reservataire = $form->getData()->getUser()->getId();
                }               
                $_SESSION['user'] = $user;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;             
                $_SESSION['instructeur'] = $instructeur;
                $_SESSION['auteur'] = $auteur;

     
			}

           // $staff = 1;
            //$_SESSION['staff'] = $staff;

            //var_dump($staff);
           // exit;
            return $this->render('reservation_admin/index.html.twig', [
            'form' => $form->createView(),         
            ])
        ;

    }
}  

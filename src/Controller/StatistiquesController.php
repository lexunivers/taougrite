<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use App\Entity\Statistiques;
use App\Entity\User;
use App\Entity\OperationComptable;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Persistence\ManagerRegistry;

class StatistiquesController extends CRUDController
{

    public function __construct(private ManagerRegistry $doctrine) {} 

    public function preList(Request $request): ?Response
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }


        $em = $this->doctrine->getManager();        
        $recettes = $em->getRepository('App\Entity\OperationComptable')->findByRecetteCB();
        $cptesDebiteurs = $em->getRepository('App\Entity\OperationComptable')->findByCptesDebiteurs();
        $totalRecettes = $em->getRepository('App\Entity\OperationComptable')->findBySommeTotaleRecette();
        $totalCredits = $em->getRepository('App\Entity\OperationComptable')->findBySommeTotaleCredit();
       // $users = $em->getRepository('App\Entity\User)')->findAll();
        //var_dump($users);
        //exit;

       // var_dump($totalCredits);
        //var_dump($totalRecettes);
       // exit;

        $UserNom = [];
        $UserColor = [];
        $UserCount = [];
        $UserDate = [];	      

        $cptesDebiteursCount = [];
        $totalRecettesCount = [];
        $totalCreditsCount = [];                        
        $datesRecettes = [];
        $datesCredits = [];
        $RecettesCount = [];
        $dates = [];
        $reservationsCount = []; 

        foreach($totalRecettes as $totalRecette){
           // $dates[] = $totalRecette[''];
            $totalRecettesCount[] = $totalRecette['total'] ;
        }

        foreach($totalCredits as $totalCredit){
           // $dateCredits[] = $totalCredit['jour'];
             $totalCreditsCount[] = $totalCredit['total'] ;
         }        

        foreach($recettes as $recette){
            $datesRecettes[] = $recette['jour'];
            $RecettesCount[] = $recette['nombre'] ;
        }

        // B - 1 On "démonte" les données pour les séparer tel qu'attendu par ChartJS
       // $em = $this->getDoctrine()->getManager(); 

   
        // A - On va chercher tous les Users
        //$em = $this->getDoctrine()->getManager();
            $Users = $em->getRepository('App\Entity\User')->findAll();

        // A - 1 On "démonte" les données pour les séparer tel qu'attendu par ChartJS		
 	
            
            foreach($Users as $User){
                $UserNom[] = $User->getFirstname();
            //    $UserDate[] = $User->getLastname()->getCreatedAt();
            // $categColor[] = $User->getColor();
            // $UserCount[] = count(is_countable($User)?$UserNom:[]);
                $UserCount[] = count(array($User));
            }
            //SELECT SUBSTRING(last_login, 1, 10) as derniere_connection, SUBSTRING(created_at, 1, 10) as inscription FROM `user` WHERE username = "marius"; 
          //  var_dump($UserDate);
//exit;
        // B - On va chercher le nombre de reservation par Jour
			$reservations = $em->getRepository('App\Entity\Reservation')->countByDate();

			// B - 1 On "démonte" les données pour les séparer tel qu'attendu par ChartJS
			$dates = [];
			$reservationsCount = []; 

			foreach($reservations as $reservation){
				$dates[] = $reservation['jour'];
				$reservationsCount[] = $reservation['nombre'] ;
			}



        return $this->render('Statistiques/New.index.html.twig', [
            'UserNom' => json_encode($UserNom),
            'UserColor' => json_encode($UserColor),
            'UserCount' => json_encode($UserCount),
            'dates' => json_encode($dates),
            'reservationsCount' => json_encode($reservationsCount),
            'RecettesCount' => json_encode($RecettesCount),
            'totalRecettesCount' => json_encode($totalRecettesCount),
            'totalCreditsCount' => json_encode($totalCreditsCount),
            //'dateCredits' => json_encode($dateCredits),
            ])

        ;

        //return $this->render('/Admin/CpteIndividuel/cpte_solo.html.twig',     
       // return $this->render('statistiques/index.html.twig');
    }


}

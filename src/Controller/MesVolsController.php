<?php

namespace App\Controller;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Avions;
use App\Repository\AvionsRepository;
use App\Entity\Vol;
use App\Form\VolType;
use App\Form\VolEditType;
use App\Entity\OperationComptable;
use App\Repository\VolRepository;
use App\Entity\User;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Knp\Component\Pager\PaginatorInterface;
//use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Validator\Constraints\DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Form\FormFactoryBuilder;
use Doctrine\Persistence\ManagerRegistry;


class MesVolsController extends AbstractController
{

    // Method 2: via constructor
    //public function __construct(FlashyNotifier $flashy)
    //{
    //    $this->flashy = $flashy;
    //}
    public function __construct(private ManagerRegistry $doctrine) {} 

    #[Route('/vol', name: 'app_MesVols')]  
    public function SaisirUnVolAction(Vol $vol = null,  Request $request, ObjectManager $manager = null, OperationComptable $operation = null, Avions $avion = null)
    {
        $vol = new Vol();    
        $vol->setUser($this->container->get('security.token_storage')->getToken()->getUser());    
        $reservataire = $this->container->get('security.token_storage')->getToken()->getUser();                
        $em = $this->doctrine->getManager();

        $form = $this->createForm(VolType::class, $vol, array('reservataire' => $reservataire ));        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vol->setFacture($vol->getMontantFacture());

            // Récupérer le code de réservation choisi dans le formulaire
            $CodeReservation = $form->get('CodeReservation')->getData();

            // Mettre à jour l'attribut Realisation de la réservation
            if ($CodeReservation) {
                $CodeReservation->setRealisation(true);
                $CodeReservation->getAppareil();

                $avion = $form->get('avion')->getData();

                $avion2 = $CodeReservation->getAppareil();
                $appareil = $avion->getType();

			}

            // Enregistrer les modifications dans la base de données
            $em = $this->doctrine->getManager();
            $em->persist($vol);
            $em->flush();

            $request->getSession()->getFlashBag()->add('message', 'Le Vol a bien été enregistré.');

            return $this->redirect($this->generateUrl('app_MesVols_confirmer', ['id' => $vol->getId(), 'avion' => $vol->getAvion('id'), 'Heuresdevol' => $vol->DureeDuVol(), ]));
        }
         
        return $this->render('/MesVols/saisir_Un_Vol.html.twig', [
            'formVol'    => $form->createView(),
            'editMode' => $vol->getId() !== null,
            'reservataire' =>$reservataire,
            ]);
    }

    /**
     * @Route("/confirmer/vol/{id}", name="app_MesVols_confirmer")
     */	 

     #[Route('/confirmer/vol/{id}', name:'app_MesVols_confirmer')]       
    public function ConfirmerUnVolAction(Vol $vol = null, Request $request, OperationComptable $operation = null, Avions $avion = null, $id)
    {
        $operation = new OperationComptable();
        $operation->setCompteId($this->container->get('security.token_storage')->getToken()->getUser()->getId());
        $operation->setUser($vol->getUser());
        $operation->setOperMontant($vol->getMontantFacture());
        $operation ->setOperSensMt(0);
        $operation->setLibelle($vol->getLibelle());
        		
        $avion = $vol->getAvion('id');

        $em = $this->doctrine->getManager();
				
		//<-- Pour les Heures de Fonction du moteur/cellule
		//------------------------------------------------------------------------
		$totalF = $vol->HeuresdeF();
		$temps = $vol->DureeDuVol();
		$totalFcellule = $vol->HeuresdeFcellule();
        
      //  echo 'heure cellule '.$totalFcellule;
		$heure_1=$totalF;
		$heure_1_1=$totalFcellule;
        echo 'heure_1_1 '.$heure_1_1;
        
        var_dump($heure_1_1);
        var_dump($heure_1);
    
		$heure_2=$temps;
		echo 'La somme de '.$heure_1.' et de '.$heure_2.' est: '.$vol->add_heures($heure_1,$heure_2); 
						
		$vol->getAvion('id')->setHeuresMoteur($vol->add_heures($heure_1,$heure_2) );
		$vol->getAvion('id')->setHeuresCellule($vol->add_heures($heure_1_1,$heure_2) );
        $vol->setComptable($operation);

        if (null === $vol) {
            throw new NotFoundHttpException("Le Vol d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(VolEditType::class, $vol);

        if ($request->isMethod('POST') && $form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
			
			$em = $this->doctrine->getManager();
            $em->persist($operation);
            $em->persist($vol);
            $em->flush();

            $request->getSession()->getFlashBag()->add('message', 'la facture est inscrite dans votre Cpte Pilote.');
            // $this->flashy->primaryDark('Vol enregistré !', 'http://your-awesome-link.com'); 
            return $this->redirectToRoute('app_MesVols_detail', array('id' => $vol->getId()));
        }

        return $this->render('/MesVols/Confirmer_Un_Vol.html.twig', [
            'vol' => $vol,
            'formVol'   => $form->createView(),
            'editMode' => $vol->getId() !== null
            ]);
    }


     /**
     * @Route("/modifier/vol/{id}", name="sky_gestion_vol_modifier")
     */
    
     #[Route('/modifier/vol/{id}', name:'app_MesVols_modifier')]       
    public function ModifierUnVolAction($id, Request $request, OperationComptable $operation = null, vol $vol = null )
    {
        $em = $this->doctrine->getManager();
        $vol = $em->getRepository('App\Entity\Vol')->find($id);

        $vol->setUser($this->container->get('security.token_storage')->getToken()->getUser());


        if (null === $vol) {
            throw new NotFoundHttpException("Le Vol d'id ".$id." n'existe pas.");
        }

		// A- Pour Modifier les Heures de Fonction du moteur
		//------------------------------------------------------------------------
		    // 1 - on recupére la valeur en BDD:
			$HeureF=$vol->getAvion('id')->getHeuresMoteur();
            $totalFcellule = $vol->getAvion('id')->getHeuresCellule();
		
            // 2 - on récupére la durée précedente du vol
			$totalF = $vol->HeuresdeF();
            $totalFcellule = $vol->HeuresdeFcellule();
			$temps = $vol->DureeDuVol();

		    // 3 - on affecte les valeurs $heure_1 et heure-2  
			$heure_1=$totalF;
			$heure_2=$temps;
            $heure_1_1=$totalFcellule;

		    // 4 - on retire la précedente valeur "DureeDuVol"
			//echo 'La somme de '.$heure_1.' et de '.$heure_2.' est: '.($vol->diff_heures($heure_1,$heure_2) );
			$vol->getAvion('id')->setHeuresMoteur($vol->diff_heures($heure_1,$heure_2) );
			$vol->getAvion('id')->setHeuresCellule($vol->diff_heures($heure_1_1,$heure_2) );
		
            // 5 - on persist la soustraction
            $em = $this->doctrine->getManager();
            $em->persist($vol);

        $form = $this->createForm(VolEditType::class, $vol);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->doctrine->getManager();

		//<-- Pour Enregistrer les nouvelles Heures de Fonction du moteur/cellule
		//------------------------------------------------------------------------
			// 1 - on recupére la new valeur de fonctionnement du moteur et cellule:
				$totalF = $vol->HeuresdeF();
				$totalFcellule = $vol->HeuresdeFcellule();
			// 2 - on récupére la nouvelle durée du vol				
				$temps = $vol->DureeDuVol();
				
			// 3 - on affecte les new valeur $heure_1 et heure-2 
				$heure_1=$totalF;
				$heure_2=$temps;
				$heure_1_1=$totalFcellule;				
			//	echo 'La somme de '.$heure_1.' et de '.$heure_2.' est: '.$vol->add_heures($heure_1,$heure_2); //
		
			// 4 - on enregistre les modifications	
				$vol->getAvion('id')->setHeuresMoteur($vol->add_heures($heure_1,$heure_2));			
				$vol->getAvion('id')->setHeuresCellule($vol->add_heures($heure_1_1,$heure_2));

            //<-- Pour modifier HeureDepart/HeureArrivee/libelle/facture
            //----------------------------------------------------------
            $vol->setFacture($vol->getMontantFacture());

            $operation = $vol->getComptable();
            $operation->setOperMontant($vol->getFacture());
            $operation ->setOperSensMt(0);
            $operation->setLibelle($vol->getLibelle());

            $avion = $vol->getAvion('id');

            $em = $this->doctrine->getManager();            
            $em->persist($operation);		
            $em->flush();

            $request->getSession()->getFlashBag()->add('message', 'Le Vol a bien été modifié.');
            //$this->flashy->success('Vol modifié', 'http://your-awesome-link.com');
			//$this->flashy->primaryDark('Vol Modifié !', 'http://your-awesome-link.com');        
            
			return $this->redirectToRoute('app_MesVols_detail', array('id' => $vol->getId()));
        }

        return $this->render('/MesVols/Modifier_Un_Vol.html.twig', array(
            'vol' => $vol,
            'operation'=>$operation,
            'formVol'   => $form->createView(),
            'editMode' => $vol->getId() !== null,
          
        ));
    }


     /**
     * @Route("/supprimer/vol/{id}", name="sky_gestion_vol_supprimer")
     */
    #[Route('/supprimer/vol/{id}', name:'app_MesVols_supprimer')]	 
    public function SupprimerUnVolAction(Request $request, $id)
    {
        $em = $this->doctrine->getManager();
        $vol = $em->getRepository('App\Entity\Vol')->find(array('id'=>$id));
        $operation = $em->getRepository('App\Entity\OperationComptable')->find($id);
                    
        if (null === $vol) {
            throw new NotFoundHttpException("Le Vol d'id ".$id." n'existe pas.");
        }

        // On modifie le Total d'heures de fonctionnement moteur en BDD		
		$totalFcellule = $vol->HeuresdeFcellule();		
		$totalF = $vol->HeuresdeF();
	    $temps = $vol->DureeDuVol();

		$heure_1=$totalF;
		$heure_2=$temps;
 
		$vol->getAvion('id')->setHeuresMoteur($vol->diff_heures($heure_1,$heure_2) );
		$vol->getAvion('id')->setHeuresCellule($vol->diff_heures($heure_1,$heure_2) );

        $form = $this->createForm(VolEditType::class, $vol);

        if ($request->isMethod('POST') ) {
            //&& $form->isSubowned() && $form->handleRequest($request)->isValid()) {        
		$em->remove($vol);
        $em->flush();
                        
        $request->getSession()->getFlashBag()->add('message', "Le Vol a bien été supprimé.");
			//$this->flashy->success('Vol supprimé', 'http://your-awesome-link.com');
            
			return $this->redirectToRoute('app_MesVols_detail', array('id' => $vol->getId() ));
        }
                
        return $this->render('/MesVols/Supprimer_Un_Vol.html.twig', array(
            'vol' => $vol,
            'formVol'   => $form->createView(),
        ));
    }

     /**
     * @Route("/vol/carnetDeVols", name="app_MesVols_carnet")
     */
    #[Route('/vol/carnetDeVols', name: 'app_MesVols_carnet')]  	
    public function carnetDeVols(Vol $vol = null, VolRepository $volsRepo, Request $request , PaginatorInterface $paginator)
    {
        // attributs de session
        $user = $this->getUser('session')->getId();               
        $em = $this->doctrine->getManager();
        $vols = $em->getRepository('App\Entity\Vol')->findAll();

        $monTempsSolo = $em->getRepository('App\Entity\Vol')->myDureeTotaleSolo($user);
        $monTempsDouble = $em->getRepository('App\Entity\Vol')->myDureeTotaleDouble($user);
        $monTempsGlobal = $em->getRepository('App\Entity\Vol')->myDureeTotaleGlobal($user);
        
        foreach ($monTempsSolo as $value1) {            
            $value1;            
        };

        foreach ($monTempsDouble as $value2) {
            $value2;
        };

        foreach ($monTempsGlobal as $value3) {
            $value3;
        };        


        //-------------------- Temps solo ------------------

        if (strlen($value1) <= 5) {
            $value1 = '0'. $value1 ;                    
        }

        // Si aucun vol enregistré
        if ($monTempsSolo['SommeDureeSolo'] === null) {
            $value1[3] = '0';
            $value1[4] = '0';
           // echo" sommeDureeSolo est null";
        }
        //else{
        //    echo" sommeDureeSolo n\'est PAS null";
        //}
        //----------------------------------------------------    

        $minutesSolo = ($value1[2]).($value1[3]);
        $heuresSolo = $value1[0].$value1[1];

        if($minutesSolo >= 60){
                $minutesSolo = $minutesSolo - 60 ;
                $heuresSolo = $heuresSolo + 1;
        }   

        // Affichage Temps Solo
        if ($minutesSolo <= 9) {
            $heure1 = $heuresSolo."h".$minutesSolo;
        }else{
            $heure1 = $heuresSolo."h".$minutesSolo;
        } 
            //$heure1 = $heuresSolo."h".$minutesSolo;


        //----------------- Temps double --------------
        
        if (strlen($value2) <= 5) {
           $value2 = '0'. $value2 ;                    
        }

        if ($monTempsDouble['SommeDureeDouble'] === null) {
            $value2[3] = '0';
            $value2[4] = '0';
        //    echo" sommeDureeDouble est null";
        //}else{
        //    echo" sommeDureeDouble n\'est PAS null";          
        } 

        $minutesDouble = ($value2[2]) . ($value2[3]);
        $heuresDouble = $value2[0].$value2[1];

        if($minutesDouble > 60){           
            $minutesDouble = $minutesDouble - 60;
            $heuresDouble = ($heuresDouble) + 1;
        }

        // Affichage Temps Double
        if ($minutesDouble < 10) {
            $heure2 = $heuresDouble."h".$minutesDouble;
        }else{
            $heure2 = $heuresDouble."h".$minutesDouble;
        } 
        
        //----------------- Temps global ---------------
      
        if (strlen($value3) <= 5 ){
            $value3 = '0'. $value3 ;                    
        }

        if ($monTempsGlobal['SommeDureeGlobale'] === null) {
            $value3[3] = '0';
            $value3[4] = '0';
        } 

         $minutesGlobal = ($value3[2]) . ($value3[3]);
         $heuresGlobal = $value3[0].$value3[1];

        if($minutesGlobal > 60){
            $minutesGlobal = $minutesGlobal - 60;
            $heuresGlobal = $heuresGlobal + 1;
        } 
          
        //Affichage Temps Global
        if ($minutesGlobal < 10) {
            $heure3 = $heuresGlobal."h".$minutesGlobal;
        }else{
            $heure3 = $heuresGlobal."h".$minutesGlobal;
        } 
    
        //-----------------------------------------------------------
        $Vols = $volsRepo->findBy(array('user' => $user ),array('datevol' => 'desc') );
		
        $vols  = $paginator->paginate(
            $Vols, 
            $request->query->getInt('page', 1),
            8 /* límite par page */
        );

        return $this->render('/MesVols/carnetDeVols.html.twig', array(
            'vols' => $vols,
            'monTempsSolo' =>$heure1,
            'monTempsDouble' =>$heure2,
            'monTempsGlobal' =>$heure3,
           // 'sommeDureeVol' =>[$sommeDureeVol],
        ));
    }    

     /**
     * @Route("/vol/liste_des_vols", name="app_MesVols_liste")
     */
    #[Route('/vol/liste_des_vols', name: 'app_MesVols_liste')]  
    public function listdesvolsAction(VolRepository $volsRepo, Request $request , PaginatorInterface $paginator)
    {
        // attributs de session
        $user = $this->getUser('session')->getId();
                
        $em = $this->doctrine->getManager();

        $Vols = $volsRepo->findBy(
            array('user' => $user ),
            array('datevol' => 'desc')
        );
		
        $vols  = $paginator->paginate(
            $Vols, 
            $request->query->getInt('page', 1),
            7 /* límite por página */
        );

        return $this->render('/MesVols/ListeVols.html.twig', array(
            'vols' => $vols,

        ));
    }


     /**
     * @Route("/vol/details_des_vols", name="sky_gestion_vols_detail")
     */
    #[Route('/vol/details_des_vols', name:'app_MesVols_detail' )]	
    public function detailsdesvolsAction(VolRepository $volsRepo, Request $request, PaginatorInterface $paginator)
    {
        // attributs de session
        $user = $this->getUser('session')->getId();
        $em=$this->doctrine->getManager();

        $Vols = $volsRepo->findBy(
            array('user'=> $user),
            array('datevol' => 'desc'),

        );

        $vols  = $paginator->paginate(
            $Vols, 
            $request->query->getInt('page', 1), 
            4 
        );        
            
        return $this->render(
            '/MesVols/Details_des_Vols.html.twig',
            array('vols' => $vols,
        )
        );
    }


      /**
     * @Route("/vol/pdf_list_vols", name="sky_PDFlistVols")
     */
    #[Route('/vol/pdf_list_vols', name:'app_PDFlistVols' )]	  
    public function PDFlistVolsAction( Request $request)
    {
   
       // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        
        $pdfOptions->set('defaultFont', 'Courier')->setChroot("C:\\wamp64\\www\\dahra\\public");

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $em = $this->doctrine->getManager();
        $user = $this->getUser('session')->getId();        
        $vols = $em->getRepository('App\Entity\Vol')->findBy(
            array('user' => $user ),
            array('datevol' => 'desc')
        );
      
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('MesVols/PDF_listeVols.html.twig', ['vols'=>$vols, 'user'=> $user ] ); 
     
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("PDF_listeVols.pdf", [
            "Attachment" => true
        ]);        

    }

    /**
    * @Route("/vol/pdf_detail_vols", name="sky_PDFlistDetailVols")
    */
    #[Route('/vol/pdf_detail_vols', name:'app_PDFlistDetailVols' ) ]	  
    public function PDFlistDetailVolsAction( Request $request)
    {
        
       // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        
        $pdfOptions->set('defaultFont', 'Courier')->setChroot("C:\\wamp64\\www\\dahra\\public");
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $em = $this->doctrine->getManager();
        $user = $this->getUser('session')->getId();        
        $vols = $em->getRepository('App\Entity\Vol')->findBy(
            array('user' => $user ),
            array('datevol' => 'desc')
        );
      
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('MesVols/PDF_DetailVols.html.twig', ['vols'=>$vols, 'user'=>$user ] ); 
     
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("PDF_DetailVols.pdf", [
            "Attachment" => true
        ]);        

    //sortie
        // Store PDF Binary Data
        //$output = $dompdf->output();
        
        // In this case, we want to write the file in the public directory
        //$publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
        
        // e.g /var/www/project/public/mypdf.pdf
        //$pdfFilepath =  $publicDirectory . '/mypdf.pdf';
        
        // Write file to the desired path
        //file_put_contents($pdfFilepath, $output);
        
        // Send some text response
        return new Response("The PDF file has been succesfully generated !");
    //
    }

    #[Route('/vol/PDFcarnetdeVols', name:'app_MesVols_PDFcarnet')]    	  
    public function PDFcarnetdeVolsAction( Request $request)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        
        $pdfOptions->set('defaultFont', 'Courier')->setChroot("C:\\wamp64\\www\\sf5\\public");

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $em = $this->doctrine->getManager();
        $user = $this->getUser('session')->getId();        
        $vols = $em->getRepository('App\Entity\Vol')->findBy(
            array('user' => $user ),
            array('datevol' => 'desc')
        ); 
        $monTempsSolo = $em->getRepository('App\Entity\Vol')->myDureeTotaleSolo($user);
        $monTempsDouble = $em->getRepository('App\Entity\Vol')->myDureeTotaleDouble($user);
        $monTempsGlobal = $em->getRepository('App\Entity\Vol')->myDureeTotaleGlobal($user);

        foreach ($monTempsSolo as $value1) {            
            $value1;
        };

        foreach ($monTempsDouble as $value2) {
            $value2;
        };

        foreach ($monTempsGlobal as $value3) {
            $value3;
        };        

        //-------------------- Temps solo ------------------

        if (strlen($value1) <= 5) {
            $value1 = '0'. $value1 ;                    
        }

        $minutesSolo = ($value1[2]).($value1[3]);
        $heuresSolo = $value1[0].$value1[1];

        if($minutesSolo >= 60){
                $minutesSolo = $minutesSolo - 60 ;
                $heuresSolo = $heuresSolo + 1;
        }   

        // Affichage Temps Solo
        if ($minutesSolo <= 9) {
            $heure1 = $heuresSolo."h"."0".$minutesSolo;
        }else{
            $heure1 = $heuresSolo."h".$minutesSolo;
        } 
            //$heure1 = $heuresSolo."h".$minutesSolo;


        //----------------- Temps double --------------
        
        if (strlen($value2) <= 5) {
           $value2 = '0'. $value2 ;                    
        }
        
        $minutesDouble = ($value2[2]) . ($value2[3]);
        $heuresDouble = $value2[0].$value2[1];

        if($minutesDouble > 60){           
            $minutesDouble = $minutesDouble - 60;
            $heuresDouble = ($heuresDouble) + 1;
        }

        // Affichage Temps Double
        if ($minutesDouble < 10) {
            $heure2 = $heuresDouble."h".$minutesDouble;
        }else{
            $heure2 = $heuresDouble."h".$minutesDouble;
        } 
        

        //----------------- Temps global ---------------
      
        if (strlen($value3) <= 5 ){
            $value3 = '0'. $value3 ;                    
        }
         
         $minutesGlobal = ($value3[2]) . ($value3[3]);
         $heuresGlobal = $value3[0].$value3[1];

        if($minutesGlobal > 60){
            $minutesGlobal = $minutesGlobal - 60;
            $heuresGlobal = $heuresGlobal + 1;
        } 
          
        //Affichage Temps Global
        if ($minutesGlobal < 10) {
            $heure3 = $heuresGlobal."h".$minutesGlobal;
        }else{
            $heure3 = $heuresGlobal."h".$minutesGlobal;
        } 

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('MesVols/PDF_CarnetDeVols.html.twig', array(
        'vols' => $vols,
        'user'=> $user, 
        'monTempsSolo' =>$heure1,
        'monTempsDouble' =>$heure2,
        'monTempsGlobal' =>$heure3,
         ) ); 
     
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("PDFcarnetdeVols.pdf", [
            "Attachment" => true
        ]);        

    }
}

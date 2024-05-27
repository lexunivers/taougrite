<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
use App\Entity\CodeAttribue;
use App\Form\ReservationEditType;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Knp\Component\Pager\PaginatorInterface;
Use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Serializer\SerializerInterface;
use DoctrineExtensions\Query\Mysql;
use Doctrine\ORM;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Serializer;


class ReservationController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {} 
    /**
     * @Route("/reservation", name="reservation_index", methods={"GET"})
     */
    #[Route('/reservation', name:'reservation_index', methods:['GET']) ]
    public function index(ReservationRepository $reservationRepository): Response
    {
		$editMode = false;
		$reservation = new Reservation();
        $form = $this->createForm(ReservationEditType::class, $reservation);		
        	        
		return $this->render('reservation/index.html.twig', [
		    'editMode' => $editMode,
            'form' => $form->createView(),
            'reservation' => $reservationRepository->findAll(),
        ]);
    }

    /**
    * @Route("/reservation/load", name="reservation_load")
    */
    #[Route('/reservation/load', name:'reservation_load') ]
    public function load(SerializerInterface $serializer)
    {
        $em = $this->doctrine->getManager();       
        $liste = $em->getRepository('App\Entity\Reservation')->findAll();      
        $json = $serializer->serialize($liste, 'json');       
        return new Response($json); 
    }
    
    /**
    * @Route("/reservation/avion", name="reservation_avion")
    */
    #[Route('/reservation/avion', name:'reservation_avion') ]    
    public function avion(SerializerInterface $serializer)
    {
        $em = $this->doctrine->getManager();       
		$avion = $em->getRepository('App\Entity\Avions')->findAll();//myfindCalendar();    
        $json = $serializer->serialize($avion, 'json');
        return new Response($json); 
    }

    /**
    * @Route("/reservation/resources", name="reservation_resources")
    */
    #[Route('/reservation/resources', name:'reservation_resources') ]
    public function resources(SerializerInterface $serializer)
    {
        $em = $this->doctrine->getManager();       
		$resources = $em->getRepository('App\Entity\Resources')->findAll();
        $json = $serializer->serialize($resources, 'json');
	//dd($json);
        return new Response($json); 
    }
 
    /**
    * @Route("/reservation/instructeur", name="reservation_instructeur", requirements={"editMode" = "1"}, methods={"GET","POST"})
    */
    #[Route('/reservation/instructeur', name:'reservation_instructeur', methods:['GET','POST']) ]
    public function instructeur(Request $request): Response
    {
        $em = $this->doctrine->getManager();       
		$instructeur = $em->getRepository('App\Entity\Instructeur')->findAll();    
        $json = $serializer->serialize($instructeur, 'json');
	
        return new Response($json);		
	}
    
    
    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     */
    #[Route('/new', name:'reservation_new', methods:['GET','POST']) ]
    public function new(Request $request): Response
    {
		// attributs de session
       // $session = $this->get("session");
        $auteur = $this->getUser('session')->getId();
        $user = $this->getUser('session')->getUsername();

        if ($user == "admin"){
            $staff = 1;
        }else{
            $staff = 0;
        }        

        if($request->isXmlHttpRequest()) {
            
            $id = $request->get('id');            
               
            // On instancie une nouvelle reservation
            $reservation = new Reservation();

            $title = $request->get('user');
            $start = $request->get('start');
            $end = $request->get('end');
            $resourceId = $request->get('resourceId');
            $formateur = $request->get('formateur');
            $instructeur = $request->get('instructeur');
            $avion = $request->get('avion');
            $appareil = $request->get('appareil');

            $em = $this->doctrine->getManager();
            
            // Une réservation est faite:
                // a - soit par le Pilote lui-même dans son espace,
                // b - soit par le club
            // Si la réservation est faite par le Club, il faut changer "admin" par le nom du pilote
            
            if($user == "admin"){
              $user = $_SESSION['user'];
              $nom = $_SESSION['nom'];
              $prenom = $_SESSION['prenom'];
              $instructeur = $_SESSION['instructeur'];
              $auteur = $_SESSION['auteur'];
              $reservation->setReservataire($auteur);
              $user = $nom;
            }

            // on vérifie s'il y a un instructeur
            if ($instructeur == false ){               
			    
                // - Si Réservation par la secrétaire club. Vol Solo
                if($staff == 1){
                    $reservation->setTitle($user."/Club");
                }else{
                    $reservation->setTitle($user);
                }
                
                $reservation->setFormateur("Néant");
                $reservation->setUser($title);          

                $appareil = $em->getRepository('App\Entity\Resources')->myfindAvion($resourceId);           
                
                foreach ($appareil as $value) {
					$value;
				};
                $appareil = implode("--", $value);
                $reservation->setAppareil($appareil);              
             
            }else{

                $formateur = $em->getRepository('App\Entity\Instructeur')->myfindNom($instructeur);              		
				$initiale = $em->getRepository('App\Entity\Instructeur')->myfindInitiales($instructeur);				
                $appareil = $em->getRepository('App\Entity\Resources')->myfindAvion($resourceId);

                // - 1 - On renseigne l'attribut 'Appareil'
                foreach ($appareil as $value1) {
					$value1;
				};
                $appareil = implode("--", $value1);
                $reservation->setAppareil($appareil);

                // - 2 - On renseigne l'attribut 'formateur'
                foreach ($formateur as $value2) {
					$value2;
				};
                $nom = implode(":", $value2);                 
                $reservation->setFormateur($nom);

                // - 3 - On renseigne l'attribut 'Initiales' Instructeur
                foreach ($initiale as $value3) {
					$value3;
				};

                // la mention 'Club': Réservation faite par secrétaire du club.Vol avec Instructeur
                if ($staff == 1){
                    $title = $user." / ".$value3."/Club";    
                }else{
                    $title = $user." / ".$value3;
                }
                $reservation->setTitle($title);

			}
                // - 4 - on attribue un code de Reservation
              //  $NumeroOrdre = rand(0,10000);

                $liste = "0123456789ABCDEF";
                $NumeroOrdre = '';
                 
                while(strlen($NumeroOrdre) != 6) {
                        $NumeroOrdre .= $liste[rand(0,15)];
                }
 
                // on enregistre le code obtenu dans CodeAttribué          
                $CodeAttribue = new CodeAttribue();
                $CodeAttribue->setNombre($NumeroOrdre); 
         
                // on renseigne attribut Realisée dans entity Reservation
                // Permet de contrôler les Réservations d'un Pilote et les vols
                // qu'il a réellement réalisés. Permet de limiter les abus de réservation.
                $realisation = "0";


			$form = $this->createForm(ReservationEditType::class, $reservation);
			$form->handleRequest($request);

            $reservation->setStart(new \DateTime($start) );
            $reservation->setEnd(new \DateTime($end) );
            $reservation->setResourceId($resourceId);
            $reservation->setNumeroOrdre($NumeroOrdre);
            $reservation->setReservataire($auteur);
            $reservation->setRealisation($realisation);


            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($reservation);
            $entityManager->persist($CodeAttribue);
            $entityManager->flush();
        }
			
            return $this->render('reservation_admin/index.html.twig', [
                'form' => $form->createView(),
                'user'=>$user,
            ]);        
    }
    
    /**
     * @Route("/update", name="reservation_update", methods={"GET","POST"})
     */
    #[Route('/update', name:'reservation_update', methods:['GET','POST']) ]
    public function update(Request $request): Response
    {

		// attributs de session
        $user = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        if($request->isXmlHttpRequest()) {
            
            $id = $request->get('id');

            // Récupère l'objet en fonction de l'@Id
            $repository = $this->doctrine->getRepository(Reservation::class);   
            $reservation = $repository->find($id);
            $reservataire = $reservation->getReservataire($id);
			$instructeur = $reservation->getInstructeur($id);
 
            // Seul le réservataire ( le User d'origine) peut modifier sa réservation 
            if ($user !== $reservataire)   
			{
                $stop = true;
            }else{
                $stop = false;
				
                //$title = $request->get($title);
                $start = $request->get('start');
                $end = $request->get('end');
                $resourceId = $request->get('resourceId');

                $reservation->setStart(new \DateTime($start) );
                $reservation->setEnd(new \DateTime($end) );
  
                $entityManager = $this->doctrine->getManager();
                $entityManager->persist($reservation);
                $entityManager->flush();            
            }
           
        }
           
        $response = new Response(json_encode($stop));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        // return $this->render('reserver/index.html.twig', //['var' => $var, 'user' => $user, 'reservataire' => $reservataire ]
            //['var' => $var,
            //'form' => $form->createView(),
              // ]
           // );
    }
    
    /**
    * @Route("/delete", name="reservation_delete", methods={"GET","POST"})
    */
    #[Route('/delete', name:'reservation_delete', methods:['GET','POST']) ]
    public function delete(Request $request): Response
    {
        // attributs de session
        $user = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
            if($request->isXmlHttpRequest()) {
                $id = $request->get('id');
                $repository = $this->doctrine->getRepository(Reservation::class);
                $reservation = $repository->find($id);                 
                $reservataire = $reservation->getReservataire($id);

            // Seul le réservataire ( le User d'origine) peut supprimer sa réservation 
               if ($user !== $reservataire)   
                {
                    $stop = true;
                }else{
                    $stop = false; 
                  
            //if ($this->isCsrfTokenValid('delete'.$reserver->getId($id), $request->request->get('_token'))) {
                $entityManager = $this->doctrine->getManager();
                $entityManager->remove($reservation);
                $entityManager->flush();
                }
        }
        $response = new Response(json_encode($stop));//($stop));
        $response->headers->set('Content-Type', 'application/json');
        return $response;        
        //return $this->render('reserver/index.html.twig');
        //return $this->redirectToRoute('reserver_index');
    }

    //<------------------------------------------------------------->
    //<-- partie hors requête Ajax de index.html.twig -->
    //<-- Utilisé pour le formulaire classique de réservation -->
        
    
    #[Route('/ajout', name:'reservation_formulaire', methods:['GET','POST'])]
    
    public function ajout(Request $request): Response
    {
 
		// attributs de session
        $session = $this->getSubscribedServices("session");
        $auteur = $this->getUser('session')->getId();
        $user = $this->getUser('session')->getUsername();

        // On instancie une nouvelle reservation
        $reservation = new Reservation();

        // On attribue un code de Reservation
        //$NumeroOrdre = rand(0,10000);
        $liste = "0123456789ABCDEF";
        $NumeroOrdre = '';
         
        while(strlen($NumeroOrdre) != 6) {
                $NumeroOrdre .= $liste[rand(0,15)];
        }        
        
        
        // on enregistre le code obtenu dans CodeAttribué          
        $CodeAttribue = new CodeAttribue();
        $CodeAttribue->setNombre($NumeroOrdre); 
         
        // on renseigne attribut Realisée dans entity Reservation
        // Permet de contrôler les Réservations d'un Pilote et les vols
        // qu'il a réellement réalisés. Permet de limiter les abus de réservation.
        $realisation = "0";

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

		$instructeur = $reservation->getInstructeur();
		$appareil = $reservation->getAvion();

        $em = $this->doctrine->getManager(); 

			if ($instructeur == true){
				$initiale = $reservation->getInstructeur()->getInitiales();
				$title	= $user." / ".$initiale;
				$reservation->setTitle($title);
                $reservation->setFormateur($reservation->getInstructeur() );
			}else{			
			    $reservation->setTitle($user);
                $reservation->setFormateur("Néant");
			}

        $reservation->setAppareil($reservation->getAvion()->getImmatriculation() );    
		$reservation->setRealisation($realisation);
        $reservation->setNumeroOrdre($NumeroOrdre);			
		$reservation->setReservataire($auteur);
        $reservation->setResourceId($reservation->getAvion()->getId() );
		$reservation->setInstructeur($reservation->getInstructeur() );
        

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

            return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reserver/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/liste', name:'reserver_liste', methods:['GET','POST'] )]    
    public function listeMesReservations(Request $request, PaginatorInterface $paginator)
        {
                $reservataire = $this->getUser('session')->getId();
                $auteur = $this->getUser('session')->getId();
                $user = $this->getUser('session')->getUsername();
              
                if($user == "admin"){

                    $user = $_SESSION['user'];
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];
                    $instructeur = $_SESSION['instructeur'];
                    $reservation->setReservataire($user = $_SESSION['user']); 
                    $user = $prenom;

                }
                $user = $this->getUser('session')->getUsername();
                $em = $this->doctrine->getManager();

                $reservation = $em->getRepository('App\Entity\Reservation')->findBy(array('reservataire' => $reservataire), 
                                                                                       array('start' => 'ASC') ) ;
                
                $donnees = $em->getRepository('App\Entity\Reservation')->findAll();
                foreach ($donnees as $value1) {            
                    $value1;            
                };
               // $value1->getId();
                $value1->getStart();
                $value1->getEnd();
                $value1->getAppareil();
                $value1->getFormateur();
                $value1->getNumeroOrdre();

               // var_dump($value1->getDatevol());
              //  var_dump($value1->getStart());
             //   var_dump($value1->getId());
             //   var_dump($value1->getAppareil());
              //  var_dump($value1->getFormateur());
             //   var_dump($value1->getNumeroOrdre());
                //$form = getValue1();
                //var_dump($form);
             //   exit;
               // $donnees->getAvion()->getId();
               // $donnees->getHeureDepart();
                //$article = $articleRepository->find($id);
               // $form = $this->createForm(ReservationType::class, $reservation);
                $reservation  = $paginator->paginate(
                $reservation, 
                $request->query->getInt('page', 1),
                    5 /* límite por página */
                );

                return $this->render('/Reserver/show.html.twig', array(
                  'reservation' => $reservation,
                  'donnees' =>$value1->getId(),
                              $value1->getStart(),    
                  $value1->getEnd(),
                  $value1->getAppareil(),
                  $value1->getFormateur(),
                  $value1->getNumeroOrdre(), //'editMode'=>$editMode,
              ));
        }    

}


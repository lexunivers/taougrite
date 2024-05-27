<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\ProfileEditType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Qualification;


class UserController extends AbstractController
{

	public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/profile/modifier", name="user_profile_modifier")
     */
    #[Route('/user/profile/modifier', name: 'user_profile_modifier')]	 
	public function editProfile(Request $request)
    {
        $user = $this->getUser();//->getId();		
        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {          
            $em = $this->doctrine->getManager();
			$em-> persist($user);
			$em->flush();

            return $this->redirectToRoute('user_profile_voir');
        }
        return $this->render('User/Profile/edit_content.html.twig', [
            'form' => $form->createView(),
      //      'reservataire' =>$form->getReservataire(),
        ]);	
	}


    #[Route('/user/profile/voir', name: 'user_profile_voir')]	 
	public function showProfile(Request $request)
    {
        $user = $this->getUser();//->getId();		
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {           
            $em = $this->doctrine->getManager();
			$em-> persist($user);
			$em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('User/Profile/show_content.html.twig', [
            'form' => $form->createView(),
			'firstname' => $user->getFirstname(),
			'lastname'=> $user->getLastname(),
			'username'=>$user->getUsername(),
            'qualifications' =>$user->getQualifications(),          
       //     'reservataire' =>$form->getUser()->getId(),
			'user' =>$user
        ]);	
	}
    
    #[Route('/user/pass/modifier', name: 'user_pass_modifier')]	 
	public function editPass(Request $request, UserPasswordHasherInterface $passwordEncoder )
    {
        if($request->isMethod('POST')){

            $em = $this->doctrine->getManager();
            $user = $this->getUser();
            //on vérifie si les 2 mots de pass sont identiques
            if($request->request->get('pass') == $request->request->get('pass2') ) {
                $user->setPassword($passwordEncoder->hashPassword($user, $request->request->get('pass') ) );
                $em->flush();
               
                $this->addFlash('message', 'Mot de Pass mis à jour avec succés !');


                return $this->redirectToRoute('app_MesDossiers');
            }else{
                $this->addFlash('error', 'les 2 mots de pass ne corespondent pas');
            }
        }
        return $this->render('User/Profile/ModifPass.html.twig');  
	}     
}

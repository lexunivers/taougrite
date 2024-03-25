<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\ProfileEditType;
use Doctrine\Persistence\ManagerRegistry;


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
			'user' =>$user
        ]);	
	}	
}

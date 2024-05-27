<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesDocsController extends AbstractController
{
    #[Route('/mes/docs', name: 'app_mes_docs')]
    public function index(): Response
    {
        return $this->render('User/Profile/mesdocs.html.twig', [
            'controller_name' => 'MesDocsController',
        ]);
    }

    public function carnetDeVol(): Response
    {

        
        return $this->render('User/Profile/mesdocs.html.twig', [
            'controller_name' => 'MesVolsController',
        ]);            
    }    
}

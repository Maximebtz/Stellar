<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LodgeController extends AbstractController
{
    #[Route('/lodge', name: 'app_lodge')]
    public function index(): Response
    {
        return $this->render('lodge/index.html.twig', [
            'controller_name' => 'LodgeController',
        ]);
    }
}

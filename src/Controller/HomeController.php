<?php

namespace App\Controller;

use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $adverts = $this->entityManager->getRepository(Advert::class)->findAll();
        

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'adverts' => $adverts
        ]);
    }
}

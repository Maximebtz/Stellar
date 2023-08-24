<?php

namespace App\Controller;

use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    

    #[Route('/user/myAdverts', name: 'user_adverts')]
    #[IsGranted('ROLE_USER')]
    public function userAdverts(): Response
    {
        $adverts = $this->entityManager->getRepository(Advert::class)->findAll();
        
        return $this->render('user/userAdverts.html.twig', [
            'adverts' => $adverts
        ]);
    }

    #[Route('/user/myAdverts/detail', name: 'user_advert_detail')]
    #[IsGranted('ROLE_USER')]
    public function showDetailSession($id): Response 
    {
        $repository = $this->entityManager->getRepository(Advert::class);
        $advert = $repository->find($id);

        return $this->render('user/userAdvertDetail.html.twig', [
            'advert' => $advert,
        ]);
    }
}

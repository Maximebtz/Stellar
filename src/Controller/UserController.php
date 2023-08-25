<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
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

    #[Route('/user', name: 'app_user_advert')]
    public function appMyAdvert(): Response
    {
        return $this->render('user/userAdvert.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    

    #[Route('/user/my-property', name: 'user_adverts')]
    #[IsGranted('ROLE_USER')]
    public function userAdverts(): Response
    {
        $adverts = $this->entityManager->getRepository(Advert::class)->findAll();
        
        return $this->render('user/userAdverts.html.twig', [
            'adverts' => $adverts
        ]);
    }

    #[Route('/owner/my-property/detail', name: 'user_advert_detail')]
    #[IsGranted('ROLE_USER')]
    public function showDetailSession($id): Response 
    {
        $repository = $this->entityManager->getRepository(Advert::class);
        $advert = $repository->find($id);

        return $this->render('user/userAdvertDetail.html.twig', [
            'advert' => $advert,
        ]);
    }

    #[Route('/owner/my-property/{id}/delete', name: 'delete_advert')] // Définition de la route avec un paramètre 'id' et du nom de la route
    public function delete(AdvertRepository $advertRepository, EntityManagerInterface $entityManager, $id)
    {   
        $advert = $advertRepository->find(($id));
        $entityManager->remove($advert);
        $entityManager->flush();

        return $this->redirectToRoute('user_adverts');
    }
}

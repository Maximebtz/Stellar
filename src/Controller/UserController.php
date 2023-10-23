<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Advert;
use App\Entity\Reservation;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Security\Core\Security;
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

    #[Route('/user/profil', name: 'user_profil')]
    #[IsGranted('ROLE_USER')]
    public function profilPage(Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $userId = $user->getId();

        $numberOfAdverts = $entityManager->getRepository(Advert::class)->count(['owner' => $userId]);
        $numberOfReservations = $entityManager->getRepository(Reservation::class)->count(['user' => $userId]);
        $numberOfUniqueReservationsForOwner = $entityManager->getRepository(Reservation::class)->countUniqueReservationsForOwner($userId);


        return $this->render('user/userProfil.html.twig', [
            'user' => $user,
            'numberOfAdverts' => $numberOfAdverts,
            'numberOfReservations' => $numberOfReservations,
            'numberOfUniqueReservationsForOwner' => $numberOfUniqueReservationsForOwner,
        ]);
    }

    #[Route('/owner/my-property/detail', name: 'user_advert_detail')]
    #[IsGranted('ROLE_USER')]
    public function showDetailProperty($id): Response
    {
        $repository = $this->entityManager->getRepository(Advert::class);
        $advert = $repository->find($id);

        return $this->render('user/userAdvertDetail.html.twig', [
            'advert' => $advert,
        ]);
    }

    #[Route('/owner/my-property/{id}/delete', name: 'delete_advert')]
    #[IsGranted('ROLE_USER')]
    public function delete(Advert $advert, AdvertRepository $advertRepository, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager)
    {
        // Vérifier si il y a des réservations associée à cette annonce
        $reservations = $reservationRepository->findBy(['advert' => $advert]);

        // Si des réservations existent, empêcher la suppression
        if (!empty($reservations)) {
            $this->addFlash('danger', 'Impossible de supprimer cette annonce car des réservations y sont associées.');
            return $this->redirectToRoute('user_adverts'); // Rediriger l'utilisateur où on le souhaite
        }

        // Si aucune réservation n'est associée, on peut supprimer l'annonce
        $entityManager->remove($advert);
        $entityManager->flush();

        $this->addFlash('success', 'L\'annonce a été supprimée avec succès.');
        return $this->redirectToRoute('user_adverts');
    }
}

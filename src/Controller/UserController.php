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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

        $numberOfAdverts = $entityManager->getRepository(Advert::class)->count(['owner' => $user]);
        $numberOfReservations = $entityManager->getRepository(Reservation::class)->count(['user' => $user]);
        $numberOfOwnerReservations = $entityManager->getRepository(Reservation::class)->numberOfOwnerReservations($user);
        $myReservations = $entityManager->getRepository(Reservation::class)->findBy(['user' => $user]);
        $myAdvertsReservations = $entityManager->getRepository(Reservation::class)->findReservationsByOwner($user);
        $myAdverts = $entityManager->getRepository(Advert::class)->findBy(['owner' => $user]);


        return $this->render('user/userProfil.html.twig', [
            'user' => $user,
            'numberOfAdverts' => $numberOfAdverts,
            'numberOfReservations' => $numberOfReservations,
            'numberOfOwnerReservations' => $numberOfOwnerReservations,
            'myReservations' => $myReservations,
            'myAdverts' => $myAdverts,
            'myAdvertsReservations' => $myAdvertsReservations
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
            return $this->redirectToRoute('user_adverts');
        }

        // Si aucune réservation n'est associée, on peut supprimer l'annonce
        $entityManager->remove($advert);
        $entityManager->flush();

        $this->addFlash('success', 'L\'annonce a été supprimée avec succès.');
        return $this->redirectToRoute('user_adverts');
    }

    #[Route('/user/delete', name: 'delete_user')]
    #[IsGranted('ROLE_USER')]
    public function deleteUser(Security $security, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, SessionInterface $session,
    TokenStorageInterface $tokenStorage): Response
    {
        $user = $security->getUser();

        // Vérifier si l'utilisateur a des réservations en cours
        $reservations = $reservationRepository->findBy(['user' => $user]);

        if (!empty($reservations)) {
            $this->addFlash('danger_delete_user', 'Impossible de supprimer ce compte car des réservations sont en cours !');
            return $this->redirectToRoute('user_profil');
        }

        // Anonymisation des données
        // $user->setEmail('anonyme' . $user->getId() . '@anonyme.com');
        // $user->setUsername('anonyme' . $user->getId());

        // $entityManager->persist($user);
        // $entityManager->flush();

        // Déconnecter l'utilisateur
        $tokenStorage->setToken(null);
        $session->invalidate();

        // Suppression du compte
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success_user_delete', 'Le compte a été supprimé avec succès.');
        return $this->redirectToRoute('app_home');
    }
}

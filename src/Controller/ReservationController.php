<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/reservation/advert/{id}', name: 'app_reservation')]
    #[IsGranted('ROLE_USER')]
    public function index($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $this->entityManager->getRepository(Advert::class);
        $advert = $repository->find($id);
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez la réservation en base de données
            $reservation = $form->getData();

            $entityManager->persist($reservation);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une page de confirmation, par exemple
            return $this->redirectToRoute('reservation_confirmation');
        }

        return $this->render('reservation/index.html.twig', [
            'formAddReservation' => $form->createView(),
            'advert' => $advert,
        ]);
    }

    #[Route('/reservation/confirmation', name: 'reservation_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('reservation/confirmation.html.twig');
    }
}


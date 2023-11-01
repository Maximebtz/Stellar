<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Reservation;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/payment/create-session-stripe/{id}', name: 'payement_stripe')]
    public function stripeCheckout($id): RedirectResponse
    {
        // Récupérer la réservation à partir de l'ID
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);

        // Si l'annonce n'existe pas
        if (!$reservation) {
            $this->addFlash('error_payment', 'La réservation n\'existe plus.');
            return $this->redirectToRoute('app_home'); // Route de redirection
        }
        // dd($reservation);

        // Définir la clé secrète de Stripe
        Stripe::setApiKey('sk_test_51NuyYqByQ52rls2ZjSUdPf8cCkSCFiVi57LNB3KNdtHbTQ3f9nNUfY5UFUQnFjbe23L4EfYlUdzw9hT7PeyhTiMr008hHskPhA');

        // Récupérer le prix total de la réservation
        $totalPrice = $reservation->getPrice();

        // Créer une session de paiement Stripe
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Réservation',
                    ],
                    'unit_amount' => $totalPrice * 100, // Stripe compte en centimes donc il faut faire la conversion
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL), // Route de succès 
            'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL), // Route d'échec
        ]);
        // dd($checkout_session);

        return $this->redirect($checkout_session->url);
    }

    // Rende rde la vue de succès de paiement
    #[Route('/payment/success', name: 'payment_success')]
    public function stripeSuccess(): Response
    {
        return $this->render(view: 'payment/success.html.twig');
    }

    // Render de la vue d'échec de paiement
    #[Route('/payment/error', name: 'payment_error')]
    public function stripeError(): Response
    {
        return $this->render(view: 'payment/error.html.twig');
    }
}

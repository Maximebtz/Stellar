<?php

namespace App\Controller;

use App\Service\PdfGenerator;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    #[Route('/download-pdf/{id}', name: 'download_pdf')]
public function downloadPdf(int $id, PdfGenerator $pdfGenerator, ReservationRepository $reservationRepository): Response
{
    $reservation = $reservationRepository->find($id);
    if (!$reservation) {
        throw $this->createNotFoundException('Réservation non trouvée.');
    }

    $html = $this->renderView('pdf/invoice.html.twig', [
        'reservation' => $reservation,
    ]);

    $pdfContent = $pdfGenerator->generatePdf($html, 'invoice.pdf', false);

    return new Response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="invoice.pdf"'
    ]);
}
}

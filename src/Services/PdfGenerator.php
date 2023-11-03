<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    private Dompdf $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('defaultFont', 'Roboto Condensed');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('dpi', '300');
        $options->set('isJavascriptEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function generatePdf($html, $filename = 'document.pdf', $stream = true)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait'); // Fixe la taille et l'orientation du papier ici
        $this->dompdf->render();

        if ($stream) {
            $this->dompdf->stream($filename, ["Attachment" => false]);
        } else {
            // Retourne le contenu du PDF pour une réponse Symfony par exemple
            return $this->dompdf->output();
        }
    }
}

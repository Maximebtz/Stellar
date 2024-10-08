<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController extends AbstractController
{
    #[Route('/notice', name: 'app_notice')]
    public function index(): Response
    {
        return $this->render('notice/index.html.twig', [
            'controller_name' => 'NoticeController',
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {
        $page = filter_var($request->query->get('page', 1), FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
        $perPage = 20;

        $adverts = $this->entityManager->getRepository(Advert::class)
            ->findPaginatedAdverts($page, $perPage);

        $totalAdverts = $this->entityManager->getRepository(Advert::class)
            ->getTotalAdvertsCount();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'adverts' => $adverts,
            'totalAdverts' => $totalAdverts,
            'page' => $page,
            'perPage' => $perPage,
        ]);
    }
}

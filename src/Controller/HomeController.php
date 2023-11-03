<?php

namespace App\Controller;

use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $perPage = 14;
        $totalAdverts = $this->entityManager->getRepository(Advert::class)
            ->getTotalAdvertsCount();

        // Calculez le nombre total de pages nécessaires en fonction du nombre total d'annonces et du nombre par page
        $totalPages = ceil($totalAdverts / $perPage);

        // Récupérez le numéro de la page à partir de la requête
        $page = filter_var($request->query->get('page', 1), FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1, 'max_range' => $totalPages]]);

        // Récupérez les annonces pour la page actuelle
        $adverts = $this->entityManager->getRepository(Advert::class)
            ->findPaginatedAdverts($page, $perPage);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'adverts' => $adverts,
            'totalAdverts' => $totalAdverts,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ]);
    }


    #[Route('/home/présentation', name: 'first_home')]
    public function home(Request $request, EntityManagerInterface $entityManager): Response
    {

        $lowPriceAdverts = $entityManager->getRepository(Advert::class)->findBy([], ['price' => 'ASC']);
        $adverts = $entityManager->getRepository(Advert::class)->findAll();

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'lowPriceAdverts' => $lowPriceAdverts,
            'adverts' => $adverts,
        ]);
    }
}   

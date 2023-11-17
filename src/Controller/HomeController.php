<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
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
    public function index(Request $request, AdvertRepository $advertRepository): Response
    {
        $perPage = 14;
        $totalAdverts = $advertRepository->getTotalAdvertsCount();

        // Calculez le nombre total de pages nécessaires en fonction du nombre total d'annonces et du nombre par page
        $totalPages = ceil($totalAdverts / $perPage);

        // Récupérez le numéro de la page à partir de la requête
        $page = filter_var($request->query->get('page', 1), FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1, 'max_range' => $totalPages]]);

        // Récupérez les annonces pour la page actuelle en utilisant la méthode de ton repository
        $paginatedAdverts = $advertRepository->findPaginatedAdverts($page, $perPage);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'adverts' => $paginatedAdverts, // Utilise paginatedAdverts pour la pagination
            'totalAdverts' => $totalAdverts,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ]);
    }


    #[Route('/filter', name: 'filter_adverts', methods: 'POST')]
    public function filterAdvertsController(Request $request, AdvertRepository $advertRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $filteredAdverts = $advertRepository->filterAdverts($data);

        // Convertir les résultats en un tableau pour la réponse JSON
        $result = [];
        foreach ($filteredAdverts as $advert) {
            $firstImage = $advert->getImages()[0] ?? null;
            $result[] = [
                'id' => $advert->getId(),
                'title' => $advert->getTitle(),
                'city' => $advert->getCity(),
                'country' => $advert->getCountry(),
                'price' => $advert->getPrice(),
                'imgSrc' => $firstImage ? '/uploads/' . $firstImage->getUrl() : null,
                'detailURL' => $this->generateUrl('detail_advert', ['slug' => $advert->getSlug(), 'id' => $advert->getId()]),
            ];
        }

        return new JsonResponse($result);
    }
}

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

        $perPage = 24;
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

    #[Route('/filter', name: 'filter_adverts', methods: 'POST')]
    public function filterAdverts(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {


        $data = json_decode($request->getContent(), true);

        // Initialize filter parameters
        $params = [];

        // Base DQL query
        $dql = "SELECT a FROM App\Entity\Advert a LEFT JOIN a.reservations r WHERE 1=1";

        // Add conditions based on available filters
        if (!empty($data['cities'])) {
            $dql .= " AND a.city IN (:cities)";
            $params['cities'] = $data['cities'];
        }

        if (!empty($data['countries'])) {
            $dql .= " AND a.country IN (:countries)";
            $params['countries'] = $data['countries'];
        }

        if (!empty($data['priceRange'])) {
            $dql .= " AND a.price <= :priceRange";
            $params['priceRange'] = $data['priceRange'];
        }

        if (!empty($data['startDate'])) {
            $dql .= " AND r.startDate >= :startDate";
            $params['startDate'] = new \DateTime($data['startDate']);
        }

        if (!empty($data['endDate'])) {
            $dql .= " AND r.endDate <= :endDate";
            $params['endDate'] = new \DateTime($data['endDate']);
        }



        // Create and execute query
        $query = $entityManager->createQuery($dql);
        $query->setParameters($params);
        $filteredAdverts = $query->getResult();

        // Convert the results to an array
        $result = [];
        foreach ($filteredAdverts as $advert) {
            $firstImage = $advert->getImages()[0] ?? null;
            $result[] = [
                'id' => $advert->getId(),
                'title' => $advert->getTitle(),
                'city' => $advert->getCity(),
                'country' => $advert->getCountry(),
                'price' => $advert->getPrice(),
                'imgSrc' => $firstImage ? '/uploads/' . $firstImage->getUrl() : '',
                'detailURL' => $this->generateUrl('detail_advert', ['id' => $advert->getId()])
            ];
        }
        return new JsonResponse($result);
    }
}

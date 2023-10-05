<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Advert;
use App\Entity\Images;
use App\Form\AdvertType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\FileUploader;
use App\Repository\UserRepository;
use App\Repository\LodgeRepository;
use App\Repository\ImagesRepository;
use App\Repository\CategoryRepository;
use App\Repository\AccessoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class AdvertController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ReservationRepository $reservationRepository;

    public function __construct(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository)
    {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
    }

    #[Route('/advert', name: 'app_advert')]
    public function index(): Response
    {
        return $this->render('advert/index.html.twig', [
            'controller_name' => 'AdvertController',
        ]);
    }

    #[Route('/owner/add-property', name: 'new_advert')]
    #[Route('/owner/edit-property/{id}', name: 'edit_advert')]
    #[IsGranted('ROLE_USER')]
    public function new_edit(Advert $advert = null, Request $request, FileUploader $fileUploader, CategoryRepository $categoryRepository, AccessoryRepository $accessoryRepository, LodgeRepository $lodgeRepository): Response
    {
        if (!$advert) {
            $advert = new Advert();
        }


        $form = $this->createForm(AdvertType::class, $advert);

        //Récupérer les catégories
        $categories = $categoryRepository->findAll();
        $accessories = $accessoryRepository->findAll();
        $categories = $categoryRepository->findAll();
        $lodges = $lodgeRepository->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();

            if ($images) {
                // Utiliser le service d'upload pour uploader les images
                $uploadedImages = [];
                foreach ($images as $image) {
                    $uploadedImages[] = $fileUploader->upload($image);
                }

                // Associer les URLs d'images à l'annonce
                foreach ($uploadedImages as $imageUrl) {
                    $imageEntity = new Images();
                    $imageEntity->setUrl($imageUrl);
                    $advert->addImage($imageEntity);
                }
            }

            $selectedCategories = $form->get('categories')->getData();
            foreach ($selectedCategories as $category) {
                $advert->addCategory($category);
            }

            $selectedAccessories = $form->get('accessories')->getData();
            foreach ($selectedAccessories as $accessory) {
                $advert->addAccessory($accessory);
            }

            $advert->setOwner($this->getUser());

            $advert->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($advert);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('advert/new.html.twig', [
            'formAddAdvert' => $form->createView(),
            'edit' => $advert->getId(),
            'categories' => $categories,
            'accessories' => $accessories,
            'lodges' => $lodges,
        ]);
    }

    #[Route('/user/advert/detail/{id}', name: 'detail_advert')]
    #[IsGranted('ROLE_USER')]
    public function showDetailAdvert($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, AccessoryRepository $accessoryRepository, LodgeRepository $lodgeRepository, Request $request, ReservationRepository $reservationRepository, Security $security): Response
    {
        // Récupérer l'annonce à partir de l'ID dans l'URL
        $repository = $this->entityManager->getRepository(Advert::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $advert = $repository->find($id);
        $user = $userRepository->find($id);



        // Create a new Reservation associated with the Advert
        $reservation = new Reservation();
        $reservation->setAdvert($advert); // Associate the reservation with the advert

        // Create the form using the Reservation entity
        $reservationForm = $this->createForm(ReservationType::class, $reservation);

        // Vérifier si l'annonce existe
        if (!$advert) {
            throw $this->createNotFoundException('L\'annonce n\'existe pas.');
        }

        $user = $security->getUser();
        if (!$user) {
            throw new \RuntimeException('L\'utilisateur n\'est pas connecté.');
        }
        $reservation->setUser($user);

        // Gérez la soumission du formulaire de réservation
        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            // Enregistrer la réservation en base de données
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une page de confirmation ou ailleurs si nécessaire
            return $this->redirectToRoute('app_home');
        }
        $reservedDates = $reservationRepository->findReservedDatesForAdvert($advert->getId());


        // Récupérer les catégories, les accessoires et les lodges
        $categories = $categoryRepository->findAll();
        $accessories = $accessoryRepository->findAll();
        $lodges = $lodgeRepository->findAll();

        return $this->render('advert/detail.html.twig', [
            'advert' => $advert,
            'categories' => $categories,
            'accessories' => $accessories,
            'lodges' => $lodges,
            'formAddReservation' => $reservationForm->createView(),
            'reservedDates' => $reservedDates,
        ]);
    }


    #[Route('/home', name: 'filter_adverts', methods: 'POST')]
    public function filterAdverts(Request $request, EntityManagerInterface $entityManager): Response
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

        return $this->json($filteredAdverts);
    }
}

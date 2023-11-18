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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    #[Route('/user/nouvelle-propriete', name: 'new_advert')]
    #[Route('/owner/modification/{slug}/{id}', name: 'edit_advert')]
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

        // Nettoyage de la description
        $form->handleRequest($request);
        $description = $form->get('description')->getData();
        $cleanDescription = strip_tags($description);
        $advert->setDescription($cleanDescription);

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

            $advert->updateSlug(); // Mettre à jour le slug
            $this->entityManager->flush(); // Persister le slug mis à jour

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

    #[Route('/detail-annonce/{slug}/{id}', name: 'detail_advert')]
    public function showDetailAdvert(
        $id,
        $slug,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        AccessoryRepository $accessoryRepository,
        LodgeRepository $lodgeRepository,
        Request $request,
        SessionInterface $session,
        ReservationRepository $reservationRepository,
        Security $security,
    ): Response {
        // Récupérer l'annonce à partir de l'ID dans l'URL
        $advert = $entityManager->getRepository(Advert::class)->find($id);

        // Vérifier si l'annonce existe
        if (!$advert) {
            throw $this->createNotFoundException('L\'annonce n\'existe pas.');
        }

        $owner = $advert->getOwner();

        // Récupérer les annonces du propriétaire
        $ownerAdverts = $entityManager->getRepository(Advert::class)->findBy(['owner' => $owner], ['createdAt' => 'DESC']);

        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        $isLoggedIn = $user ? true : false;

        if (!$user) {
            $session->set('referer', $request->getRequestUri());
        }

        // Créer une nouvelle réservation associée à l'annonce
        $reservation = new Reservation();
        $reservation->setAdvert($advert)->setUser($user);
        $reservation->setStatus('pending');

        // Créer le formulaire de réservation
        $reservationForm = $this->createForm(ReservationType::class, $reservation);

        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {

            // Prix total de la réservation
            $price = $reservation->getTotalPrice();
            // Définir le prix pour qu'il soit sauvegardé
            $reservation->setPrice($price);

            // Récupérer les réservations actives pour cette annonce
            $activeReservations = $reservationRepository->findActiveReservationsForAdvert($id);
            $newStartDate = $reservation->getArrivalDate();
            $newEndDate = $reservation->getDepartureDate();

            $hasConflict = false;
            foreach ($activeReservations as $activeReservation) {
                if ($newStartDate < $activeReservation->getDepartureDate() && $newEndDate > $activeReservation->getArrivalDate()) {
                    $hasConflict = true;
                    break;
                }
            }

            if ($hasConflict) {
                // Les dates chevauchent, donc on ne peut pas créer une nouvelle réservation
                $this->addFlash('error', 'Ces dates ne sont pas disponibles.');
            } else {
                $entityManager->persist($reservation);
                $entityManager->flush();
                if ($reservation->getStatus() === 'pending') {
                    // Redirige vers le paiement
                    return $this->redirectToRoute('payement_stripe', ['id' => $reservation->getId()]);
                }
            }
        }

        $reservedDates = $reservationRepository->findReservedDatesForAdvert($id);

        // Récupérer les catégories, accessoires et lodges
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
            'ownerAdverts' => $ownerAdverts,
            'isLoggedIn' => $isLoggedIn,
        ]);
    }


}

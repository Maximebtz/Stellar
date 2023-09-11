<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Images;
use App\Form\AdvertType;
use App\Repository\AccessoryRepository;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use App\Repository\ImagesRepository;
use App\Repository\LodgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

        //Récupérer les catégories
        $categories = $categoryRepository->findAll();
        $accessories = $accessoryRepository->findAll();
        $categories = $categoryRepository->findAll();
        $lodges = $lodgeRepository->findAll();

        $form = $this->createForm(AdvertType::class, $advert);

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
    public function showDetailAdvert($id, CategoryRepository $categoryRepository): Response
    {
        $repository = $this->entityManager->getRepository(Advert::class);
        $advert = $repository->find($id);
        $categories = $categoryRepository->findAll();


        return $this->render('advert/detail.html.twig', [
            'advert' => $advert,
            'categories' => $categories
        ]);
    }

    //     #[Route('/owner/advert/remove-image/{id}', name: 'remove_advert_image')]
    //     #[IsGranted('ROLE_USER')]
    //     public function removeImage(Request $request, Images $images): Response
    //     {
    //         $data = json_decode($request->getContent(), true);
    //         // On verifie si le token est valide
    //         if($this->isCsrfTokenValid('delete'.$images->getId(), $data['_token'])) {
    //             // On recup le nom de l'images 
    //             $nom = $images->getUrl();
    //             // On supprime l'images 
    //             unlink($this->getParameter('images_directory').'/'.$nom);

    //             // On supprime l'images dans la base de données 
    //             $entityManager = $this->getDoctrine()->getManager();
    //             $entityManager->remove($images);
    //             $entityManager->flush();

    //             // On répond en json 
    //             return new JsonResponse(['success' => 1]);
    //         } else {

    //             return new JsonResponse(['error' => 'Token invalide'], 400);
    //         }
    //     }
    #[Route('/owner/advert/remove-image/{advertId}/{imageId}', name: 'remove_advert_image')]
    #[IsGranted('ROLE_USER')]
    public function removeImage(Request $request, $advertId, $imageId): Response
    {
        $advert = $this->entityManager->getRepository(Advert::class)->find($advertId);

        if (!$advert) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }

        $imageToRemove = null;

        foreach ($advert->getImages() as $image) {
            if ($image->getId() === $imageId) {
                $imageToRemove = $image;
                break;
            }
        }

        if ($imageToRemove) {
            // Supprimer l'image de l'annonce
            $advert->removeImage($imageToRemove);
            $this->entityManager->remove($imageToRemove);
            $this->entityManager->flush();
        }
        echo "<pre>";
        dd($advertId);
        echo "</pre>";
        // Rediriger vers la page de détails de l'annonce
        return $this->redirectToRoute('detail_advert', ['id' => $advertId]);
    }
}

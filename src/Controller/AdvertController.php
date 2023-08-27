<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Images;
use App\Form\AdvertType;
use App\Service\FileUploader;
use App\Service\PictureServices;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function new_edit(Advert $advert = null, Request $request, FileUploader $fileUploader): Response
    {
        if (!$advert) {
            $advert = new Advert();
        }

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

            $advert->setOwner($this->getUser());

            $this->entityManager->persist($advert);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('advert/new.html.twig', [
            'formAddAdvert' => $form->createView(),
            'edit' => $advert->getId()
        ]);
    }

    #[Route('/user/advert/detail/{id}', name: 'detail_advert')]
    #[IsGranted('ROLE_USER')]
    public function showDetailAdvert($id): Response
    {
        $repository = $this->entityManager->getRepository(Advert::class);
        $advert = $repository->find($id);

        return $this->render('advert/detail.html.twig', [
            'advert' => $advert,
        ]);
    }

    #[Route('/user/advert/remove-image/{advertId}/{imageId}', name: 'remove_advert_image')]
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

        // Rediriger vers la page de détails de l'annonce
        return $this->redirectToRoute('detail_advert', ['id' => $advertId]);
    }
}

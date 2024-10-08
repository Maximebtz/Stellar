<?php

namespace App\Controller;

use App\Entity\Accessory;
use App\Form\AccessoryType;
use App\Repository\AccessoryRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccessoryController extends AbstractController
{
    
    
    #[Route('/admin/accessory', name: 'app_accessory')]
    public function index(): Response
    {
        return $this->render('accessory/index.html.twig', [
            'controller_name' => 'AccessoryController',
        ]);
    }


    // Méthode pour créer une nouvelle accessory
    #[Route('/admin/accessory/add/new', name: 'new_accessory')] // Définition de la route et du nom de la route
    #[Route('/accessory/{id}/edit', name: 'edit_accessory')] // Définition de la route et du nom de la route
    public function new_edit(Accessory $accessory = null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {

        if(!$accessory) {
            // Création d'une nouvelle instance de l'entité Accessory
            $accessory = new Accessory();
        }

        // Création d'un formulaire basé sur AccessoryType et associé à l'entité Accessory
        $form = $this->createForm(AccessoryType::class, $accessory);

        // Traite la requête HTTP entrante avec le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire
            $accessory = $form->getData();
            $iconFile = $form->get('icon')->getData();
            if ($iconFile instanceof UploadedFile) {
                $fileUploader->setTargetDirectory($this->getParameter('icons_accessory_directory'));
                $iconFileName = $fileUploader->upload($iconFile);
                $accessory->setIcon($iconFileName);
            }

            // Persiste les données dans la base de données via l'entityManager
            $entityManager->persist($accessory);
            $entityManager->flush();

            // Redirige vers une autre page (remplacez 'app_accessory' par la route de destination souhaitée)
            return $this->redirectToRoute('app_home');
        }

        // Rendu du template 'accessory/new.html.twig' en passant le formulaire à afficher
        return $this->render('accessory/new.html.twig', [
            'formAddAccessory' => $form->createView(),
            'edit' => $accessory->getId()
        ]);
    }

    // Méthode pour afficher les suppr un employé
    #[Route('/accessory/{id}/delete', name: 'delete_accessory')] // Définition de la route avec un paramètre 'id' et du nom de la route
    public function delete(AccessoryRepository $accessoryRepository, EntityManagerInterface $entityManager, $id)
    {   
        $accessory = $accessoryRepository->find(($id));
        $entityManager->remove($accessory);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}

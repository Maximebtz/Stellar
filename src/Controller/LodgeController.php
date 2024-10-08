<?php

namespace App\Controller;

use App\Entity\Lodge;
use App\Form\LodgeType;
use App\Repository\LodgeRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LodgeController extends AbstractController
{
    
    
    #[Route('/admin/lodge', name: 'app_lodge')]
    public function index(): Response
    {
        return $this->render('lodge/index.html.twig', [
            'controller_name' => 'LodgeController',
        ]);
    }


    // Méthode pour créer une nouvelle lodge
    #[Route('/admin/lodge/add/new', name: 'new_lodge')] // Définition de la route et du nom de la route
    #[Route('/lodge/{id}/edit', name: 'edit_lodge')] // Définition de la route et du nom de la route
    public function new_edit(Lodge $lodge = null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {

        if(!$lodge) {
            // Création d'une nouvelle instance de l'entité Lodge
            $lodge = new Lodge();
        }

        // Création d'un formulaire basé sur LodgeType et associé à l'entité Lodge
        $form = $this->createForm(LodgeType::class, $lodge);

        // Traite la requête HTTP entrante avec le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire
            $lodge = $form->getData();
            $iconFile = $form->get('icon')->getData();
            if ($iconFile instanceof UploadedFile) {
                $fileUploader->setTargetDirectory($this->getParameter('icons_lodge_directory'));
                $iconFileName = $fileUploader->upload($iconFile);
                $lodge->setIcon($iconFileName);
            }

            // Persiste les données dans la base de données via l'entityManager
            $entityManager->persist($lodge);
            $entityManager->flush();

            // Redirige vers une autre page (remplacez 'app_lodge' par la route de destination souhaitée)
            return $this->redirectToRoute('app_home');
        }

        // Rendu du template 'lodge/new.html.twig' en passant le formulaire à afficher
        return $this->render('lodge/new.html.twig', [
            'formAddLodge' => $form->createView(),
            'edit' => $lodge->getId()
        ]);
    }

    // Méthode pour afficher les suppr un employé
    #[Route('/lodge/{id}/delete', name: 'delete_lodge')] // Définition de la route avec un paramètre 'id' et du nom de la route
    public function delete(LodgeRepository $lodgeRepository, EntityManagerInterface $entityManager, $id)
    {   
        $lodge = $lodgeRepository->find(($id));
        $entityManager->remove($lodge);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}

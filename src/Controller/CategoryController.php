<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    
    
    #[Route('/admin/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


    // Méthode pour créer une nouvelle category
    #[Route('/admin/category/add/new', name: 'new_category')] // Définition de la route et du nom de la route
    #[Route('/category/{id}/edit', name: 'edit_category')] // Définition de la route et du nom de la route
    public function new_edit(Category $category = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if(!$category) {
            // Création d'une nouvelle instance de l'entité Category
            $category = new Category();
        }

        // Création d'un formulaire basé sur CategoryType et associé à l'entité Category
        $form = $this->createForm(CategoryType::class, $category);

        // Traite la requête HTTP entrante avec le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire
            $category = $form->getData();

            // Persiste les données dans la base de données via l'entityManager
            $entityManager->persist($category);
            $entityManager->flush();

            // Redirige vers une autre page (remplacez 'app_category' par la route de destination souhaitée)
            return $this->redirectToRoute('app_home');
        }

        // Rendu du template 'category/new.html.twig' en passant le formulaire à afficher
        return $this->render('category/new.html.twig', [
            'formAddCategory' => $form->createView(),
            'edit' => $category->getId()
        ]);
    }

    // Méthode pour afficher les suppr un employé
    #[Route('/category/{id}/delete', name: 'delete_category')] // Définition de la route avec un paramètre 'id' et du nom de la route
    public function delete(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, $id)
    {   
        $category = $categoryRepository->find(($id));
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}

<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
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

    // Méthode pour créer une nouvelle advert
    #[Route('/user/advert/add/new', name: 'new_advert')] // Définition de la route et du nom de la route
    #[Route('/user/advert/{id}/edit', name: 'edit_advert')] // Définition de la route et du nom de la route
    #[IsGranted('ROLE_USER')] // Droit aux users uniquement
    public function new_edit(Advert $advert = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        if(!$advert) {
            // Création d'une nouvelle instance de l'entité Advert
            $advert = new Advert();
        }
        
        // Création d'un formulaire basé sur AdvertType et associé à l'entité Advert
        $form = $this->createForm(AdvertType::class, $advert);
        
        // Traite la requête HTTP entrante avec le formulaire
        $form->handleRequest($request);
        
        // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire
            $advert->setOwner($this->getUser()); // Récupère l'utilisateur connecté
            $advert = $form->getData();
            
            // Persiste les données dans la base de données via l'entityManager
            $entityManager->persist($advert);
            $entityManager->flush();
            
            // Redirige vers une autre page (remplacez 'app_advert' par la route de destination souhaitée)
            return $this->redirectToRoute('app_home');
        } 

        // Rendu du template 'advert/new.html.twig' en passant le formulaire à afficher
        return $this->render('advert/new.html.twig', [
            'formAddAdvert' => $form->createView(),
            'edit' => $advert->getId() // 
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
}


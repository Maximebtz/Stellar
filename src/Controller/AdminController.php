<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Lodge;
use App\Entity\Advert;
use App\Form\LodgeType;
use App\Entity\Category;
use App\Entity\Accessory;
use App\Form\CategoryType;
use App\Form\AccessoryType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/admin/manage', name: 'admin_manage')]
    #[IsGranted('ROLE_ADMIN')]
    public function manage(Request $request, FileUploader $fileUploader): Response
    {

        $reportedAdverts = $this->entityManager->getRepository(Advert::class)->findBy(['isReported' => true]);

        // Gérer l'ajout d'une catégorie
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $lodge = $categoryForm->getData();
            $iconFile = $categoryForm->get('icon')->getData();
            if ($iconFile instanceof UploadedFile) {
                $fileUploader->setTargetDirectory($this->getParameter('icons_category_directory'));
                $iconFileName = $fileUploader->upload($iconFile);
                $lodge->setIcon($iconFileName);
            }
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }

        // Gérer l'ajout d'un lodge
        $lodge = new Lodge();
        $lodgeForm = $this->createForm(LodgeType::class, $lodge);
        $lodgeForm->handleRequest($request);

        if ($lodgeForm->isSubmitted() && $lodgeForm->isValid()) {
            $lodge = $lodgeForm->getData();
            $iconFile = $lodgeForm->get('icon')->getData();
            if ($iconFile instanceof UploadedFile) {
                $fileUploader->setTargetDirectory($this->getParameter('icons_lodge_directory'));
                $iconFileName = $fileUploader->upload($iconFile);
                $lodge->setIcon($iconFileName);
            }

            $this->entityManager->persist($lodge);
            $this->entityManager->flush();
        }

        // Gérer l'ajout d'un accessoire
        $accessory = new Accessory();
        $accessoryForm = $this->createForm(AccessoryType::class, $accessory);
        $accessoryForm->handleRequest($request);

        if ($accessoryForm->isSubmitted() && $accessoryForm->isValid()) {
            $lodge = $accessoryForm->getData();
            $iconFile = $accessoryForm->get('icon')->getData();
            if ($iconFile instanceof UploadedFile) {
                $fileUploader->setTargetDirectory($this->getParameter('icons_accessory_directory'));
                $iconFileName = $fileUploader->upload($iconFile);
                $lodge->setIcon($iconFileName);
            }

            $this->entityManager->persist($accessory);
            $this->entityManager->flush();
        }

        return $this->render('admin/manage.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'lodgeForm' => $lodgeForm->createView(),
            'accessoryForm' => $accessoryForm->createView(),
            'reportedAdverts' => $reportedAdverts,
        ]);
    }

    #[Route('/advert/report/{id}', name: 'advert_report', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reportAdvert(int $id, EntityManagerInterface $entityManager): Response
    {
        $advert = $entityManager->getRepository(Advert::class)->find($id);
        $user = $this->getUser();
        
        if (!$advert || !$user) {
            return new JsonResponse(['message' => 'Erreur lors du signalement de l\'annonce.'], Response::HTTP_NOT_FOUND);
        }

        $advert->setIsReported(true);
        $advert->setReportedBy($user->getEmail());
        $entityManager->flush();

        return new JsonResponse(['message' => 'L\'annonce a été signalée.']);
    }

    #[Route('/admin/approve-report/{id}', name: 'admin_approve_report', methods: ['POST'])]
    public function approveReport(int $id): Response
    {
        $advert = $this->entityManager->getRepository(Advert::class)->find($id);

        if (!$advert) {
            throw $this->createNotFoundException('Annonce non trouvée.');
        }

        // Supprimer l'annonce de la base de données
        $this->entityManager->remove($advert);
        $this->entityManager->flush();

        $this->addFlash('success', 'Signalement approuvé et annonce supprimée.');
        return $this->redirectToRoute('admin_manage');
    }

    #[Route('/admin/reject-report/{id}', name: 'admin_reject_report', methods: ['POST'])]
    public function rejectReport(int $id): Response
    {
        $advert = $this->entityManager->getRepository(Advert::class)->find($id);

        if (!$advert) {
            throw $this->createNotFoundException('Annonce non trouvée.');
        }

        // Réinitialiser isReported à false
        $advert->setIsReported(false);
        $this->entityManager->flush();

        $this->addFlash('success', 'Signalement rejeté. Annonce réactivée.');
        return $this->redirectToRoute('admin_manage');
    }
}

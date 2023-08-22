<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{   
    // Route vers la page principale de la messagerie
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    // Route vers les messages reçus
    #[Route('/message/received', name: 'app_received')]
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }

    //Route vers les messages envoyés
    #[Route('/message/sent', name: 'app_sent')]
    public function sent(): Response
    {
        return $this->render('message/sent.html.twig');
    }

    //Route vers les messages à lire
    #[Route('/message/read/{id}', name: 'app_read')]
    public function read(Message $message, EntityManagerInterface $entityManager): Response
    {
        
        $message->setIsRead(true);
        
        $entityManager->persist($message);
        $entityManager->flush();
        return $this->render('message/read.html.twig', [
            'message' => $message,
        ]);
    }

    //Delete
    #[Route('/message/delete/{id}', name: 'app_delete')]
    public function delete(Message $message, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($message);
        $entityManager->flush();
        return $this->render('message/sent.html.twig');
    }

    
    #[Route('/message/send', name: 'send')]
    public function send(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message;
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        // Verifier si il est sub et ensuite si il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash("message", "Message envoyé avec succès");
            return $this->redirectToRoute("app_message");
        }

        return $this->render("message/send.html.twig", [
            "form" => $form->createView()
        ]);
    }
}

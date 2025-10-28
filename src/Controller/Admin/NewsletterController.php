<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Newsletter;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsletterController extends AbstractController
{

    public function __construct(
        private NewsletterRepository $newsletterRepository,
        private EntityManagerInterface $entityManager
    ){}
    #[Route('/admin/newsletter', name: 'app_newsletter_index', methods: ['GET'])]
    public function index(): Response
    {
        $this->newsletterRepository->findAll();

        return $this->render('admin/newsletter/index.html.twig', [
            'newsletters' => $this->newsletterRepository->findAll(),
        ]);
    }

    #[Route('/admin/newsletter{id}', name: 'app_newsletter_delete', methods: ['POST'])]
    public function delete(Newsletter $newsletter): Response
    {
        $this->entityManager->remove($newsletter);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le client a été suprimer avec succés.');
        return $this->redirectToRoute('app_newsletter_index');
    }
}

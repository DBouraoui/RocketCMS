<?php

namespace App\Controller;

use App\Entity\ContactSubmission;
use App\Repository\ContactFieldRepository;
use App\Service\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private ContactFieldRepository $contactFieldRepository,
        private EntityManagerInterface $entityManager,
    ){}

    #[Route('/contact', name: 'app_contact_index', methods: ['GET'])]
    public function index(): Response
    {
        $fields = $this->contactFieldRepository->findBy([], ['orderIndex' => 'ASC']);

        return $this->render('Themes/'.$this->settingsService->getTheme().'/contact/index.html.twig', [
            'fields' => $fields,
        ]);
    }

    #[Route('/contact', name: 'app_contact_post', methods: ['POST'])]
    public function insertContact(Request $request): Response
    {
        // Vérification CSRF
        $submittedToken = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('contact_form', $submittedToken)) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_contact_index');
        }

        // Récupération de tous les champs dynamiques
        $data = $request->request->all();
        unset($data['_csrf_token']); // retirer le token du tableau

        $submission = new ContactSubmission();
        $submission->setData($data)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($submission);
        $this->entityManager->flush();

        $this->addFlash('success', "Votre message a bien été envoyé !");
        return $this->redirectToRoute('app_contact_index');
    }
}

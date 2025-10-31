<?php

namespace App\Controller;

use App\Entity\ContactSubmission;
use App\Repository\MenuLinkRepository;
use App\Service\CacheService;
use App\Service\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ContactController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private EntityManagerInterface $entityManager,
        private MenuLinkRepository $menuLinkRepository,
        private CacheService $cacheService,
        private CsrfTokenManagerInterface $csrfTokenManager
    ){}

    #[Route('/contact', name: 'app_contact_index', methods: ['GET'])]
    public function index(): Response
    {
       $contact = $this->menuLinkRepository->findOneBy(['slug'=>'contact']);

       if (!$contact->isActive()) {
           return $this->redirectToRoute('app_home_index');
       }

        $fields = $this->cacheService->getContactFields();

        return $this->render('Themes/'.$this->settingsService->getTheme().'/contact/index.html.twig', [
            'fields' => $fields,
            'contact'=> $contact->getContent()
        ]);
    }

    #[Route('/contact', name: 'app_contact_post', methods: ['POST'])]
    public function insertContact(Request $request): Response
    {
        $honeypot = $request->request->get('contact_hp');

        $token = new CsrfToken('contact_field', $request->request->get('_token'));
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $this->addFlash('error', 'Le formulaire est invalide.');
            return $this->redirectToRoute('app_contact_index');
        }

        if (!empty($honeypot)) {
            // Si le champ est rempli → bot probable
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_contact_index');
        }

        // Récupération de tous les champs dynamiques
        $data = $request->request->all();
        unset($data['contact_hp']); // retirer le token du tableau
        unset($data['_csrf']); // retirer le token du tableau

        $submission = new ContactSubmission();
        $submission->setData($data)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($submission);
        $this->entityManager->flush();

        $this->addFlash('success', "Votre message a bien été envoyé !");
        return $this->redirectToRoute('app_contact_index');
    }
}

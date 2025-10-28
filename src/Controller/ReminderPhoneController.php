<?php

namespace App\Controller;

use App\Form\NewsletterType;
use App\Form\ReminderPhoneType;
use App\Repository\MenuLinkRepository;
use App\Service\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReminderPhoneController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private MenuLinkRepository $menuLinkRepository,
        private EntityManagerInterface $entityManager
    ){}

    #[Route('/reminder-phone', name: 'app_reminder_phone_index', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        $reminderPhone = $this->menuLinkRepository->findOneBy(['slug' => 'reminder-phone']);

        if (!$reminderPhone->isActive()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ReminderPhoneType::class);
        $form->handleRequest($request);

        $honeypot = $request->request->get('reminder_phone_hp');


        if (!empty($honeypot)) {
            // Si le champ est rempli → bot probable
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_reminder_phone_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $reminder = $form->getData();
            $reminder->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($reminder);
            $this->entityManager->flush();

            $this->addFlash('success', 'La demande de rappel a été envoyer');
            return $this->redirectToRoute('app_reminder_phone_index');
        }

        return $this->render('Themes/'.$this->settingsService->getTheme().'/reminderPhone/index.html.twig', [
            'form'=>$form,
            'reminderPhone' => $reminderPhone->getContent(),
        ]);
    }
}

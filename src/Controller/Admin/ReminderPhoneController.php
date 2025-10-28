<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ReminderPhone;
use App\Repository\ReminderPhoneRepository;
use App\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReminderPhoneController extends AbstractController
{

    public function __construct(
        private ReminderPhoneRepository $reminderPhoneRepository,
        private EntityManagerInterface $entityManager,
    ){}
    #[Route('/admin/reminder-phone', name: 'app_admin_reminder_phone_index', methods: ['GET'])]
    public function index(): Response
    {
        $reminder = $this->reminderPhoneRepository->findAll();

        return $this->render('admin/reminder_phone/index.html.twig', [
            'reminders' => $reminder,
        ]);
    }

    #[Route('/admin/reminder-phone/{id}', name: 'app_admin_reminder_phone_delete', methods: ['POST'])]
    public function delete(ReminderPhone $reminderPhone): Response
    {
        $this->entityManager->remove($reminderPhone);
        $this->entityManager->flush();


        $this->addFlash('success', 'Le rappel a bien été supprimer');
        return $this->redirectToRoute('app_admin_reminder_phone_index');
    }

    #[Route('/admin/reminder-phone/{id}', name: 'app_admin_reminder_phone_show', methods: ['GET'])]
    public function show(ReminderPhone $reminderPhone): Response
    {
        return $this->render('admin/reminder_phone/show.html.twig', [
            'reminder' => $reminderPhone,
        ]);
    }
}

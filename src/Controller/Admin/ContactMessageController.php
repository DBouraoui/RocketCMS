<?php

namespace App\Controller\Admin;

use App\Entity\ContactSubmission;
use App\Repository\ContactSubmissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactMessageController extends AbstractController
{
    public function __construct(
       private ContactSubmissionRepository $contactSubmissionRepository,
        private EntityManagerInterface $entityManager
    ){}

    #[Route('/admin/contact/message', name: 'app_admin_contact_message_index', methods: ['GET'])]
    public function index(): Response
    {
       $contacts= $this->contactSubmissionRepository->findAll();

        return $this->render('admin/contact_message/index.html.twig',
        [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/admin/contact/message/{id}', name: 'app_admin_contact_message_delete', methods: ['POST'])]
    public function delete(ContactSubmission $contactSubmission, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactSubmission->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($contactSubmission);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le message a été supprimer');

        }

        return $this->redirectToRoute('app_admin_contact_message_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/contact/message/{id}', name: 'app_admin_contact_message_show', methods: ['GET'])]
    public function show(ContactSubmission $contactSubmission): Response
    {

        $contactSubmission->setIsRead(true);

        $this->entityManager->flush();

        return $this->render('admin/contact_message/show.html.twig',
            [
                'contact' => $contactSubmission,
            ]);
    }
}

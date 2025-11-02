<?php

namespace App\Controller\Admin;

use App\Entity\ContactField;
use App\Form\ContactFieldType;
use App\Repository\ContactFieldRepository;
use App\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/contact/field')]
final class ContactFieldController extends AbstractController
{
    public function __construct(
        private ContactFieldRepository $contactFieldRepository,
    ){}

    #[Route(name: 'app_admin_contact_field_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/contact_field/index.html.twig', [
            'contact_fields' => $this->contactFieldRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_contact_field_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contactField = new ContactField();
        $form = $this->createForm(ContactFieldType::class, $contactField);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contactField);
            $entityManager->flush();

            $this->addFlash('success', 'Votre nouveau champ a été créer');

            return $this->redirectToRoute('app_contact_field_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/contact_field/new.html.twig', [
            'contact_field' => $contactField,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contact_field_show', methods: ['GET'])]
    public function show(ContactField $contactField): Response
    {
        return $this->render('admin/contact_field/show.html.twig', [
            'contact_field' => $contactField,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_contact_field_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContactField $contactField, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactFieldType::class, $contactField);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le champ a été modifier');

            return $this->redirectToRoute('app_admin_contact_field_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/contact_field/edit.html.twig', [
            'contact_field' => $contactField,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contact_field_delete', methods: ['POST'])]
    public function delete(Request $request, ContactField $contactField, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactField->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contactField);
            $entityManager->flush();


            $this->addFlash('success', 'Votre nouveau champ a été supprimer');
        }

        return $this->redirectToRoute('app_admin_contact_field_index', [], Response::HTTP_SEE_OTHER);
    }
}

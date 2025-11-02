<?php

namespace App\Controller\Admin;

use App\Entity\MediaLibrary;
use App\Form\MediaLibraryType;
use App\Repository\MediaLibraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/media/library')]
final class MediaLibraryController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
    ){}

    #[Route(name: 'app_admin_media_library_index', methods: ['GET'])]
    public function index(MediaLibraryRepository $mediaLibraryRepository): Response
    {
        return $this->render('admin/media_library/index.html.twig', [
            'media_libraries' => $mediaLibraryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_media_library_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mediaLibrary = new MediaLibrary();
        $form = $this->createForm(MediaLibraryType::class, $mediaLibrary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $media */
            $media = $form->get('picture')->getData();
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $mediaPost = $form->getData();
            if ($media) {
                $safeFilename = $this->slugger->slug($mediaPost->getTitle());
                $uniqueSuffix = uniqid();
                $extension = $media->guessExtension();
                $newFilename = $safeFilename . '_' . $uniqueSuffix . '.' . $extension;

                // Déplace le fichier dans le dossier uploads
                $media->move($uploadDir, $newFilename);

                // Enregistre le chemin relatif
                $mediaPost->setPicture('/uploads/' . $newFilename);
            }

            $mediaLibrary->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($mediaLibrary);
            $entityManager->flush();

            $this->addFlash('success', 'Votre média a bien été créer');

            return $this->redirectToRoute('app_admin_media_library_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/media_library/new.html.twig', [
            'media_library' => $mediaLibrary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_media_library_show', methods: ['GET'])]
    public function show(MediaLibrary $mediaLibrary): Response
    {
        return $this->render('admin/media_library/show.html.twig', [
            'media_library' => $mediaLibrary,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_media_library_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MediaLibrary $mediaLibrary, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MediaLibraryType::class, $mediaLibrary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $media */
            $media = $form->get('picture')->getData();
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

            if ($media) {
                // Supprime l’ancienne image si elle existe
                if ($mediaLibrary->getPicture() && file_exists($this->getParameter('kernel.project_dir') . '/public' . $mediaLibrary->getPicture())) {
                    unlink($this->getParameter('kernel.project_dir') . '/public' . $mediaLibrary->getPicture());
                }

                $safeFilename = $this->slugger->slug($mediaLibrary->getTitle());
                $uniqueSuffix = uniqid();
                $extension = $media->guessExtension();
                $newFilename = $safeFilename . '_' . $uniqueSuffix . '.' . $extension;

                // Déplace le fichier dans le dossier uploads
                $media->move($uploadDir, $newFilename);

                // Enregistre le chemin relatif
                $mediaLibrary->setPicture('/uploads/' . $newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Votre média a bien été modifié');

            return $this->redirectToRoute('app_admin_media_library_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/media_library/edit.html.twig', [
            'media_library' => $mediaLibrary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_media_library_delete', methods: ['POST'])]
    public function delete(Request $request, MediaLibrary $mediaLibrary, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mediaLibrary->getId(), $request->request->get('_token'))) {

            // Supprime l'image physique si elle existe
            if ($mediaLibrary->getPicture() && file_exists($this->getParameter('kernel.project_dir') . '/public' . $mediaLibrary->getPicture())) {
                unlink($this->getParameter('kernel.project_dir') . '/public' . $mediaLibrary->getPicture());
            }

            $entityManager->remove($mediaLibrary);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Votre média a bien été supprimer');

        return $this->redirectToRoute('app_admin_media_library_index', [], Response::HTTP_SEE_OTHER);
    }

}

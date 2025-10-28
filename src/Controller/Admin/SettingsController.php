<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\SettingsType;
use App\Repository\SettingsRepository;
use App\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    public function __construct(
        private SettingsRepository $settingsRepository,
        private CacheService $cacheService,
        private EntityManagerInterface $entityManager
    ){}
    #[Route('/admin/settings', name: 'app_admin_settings')]
    public function settings(Request $request): Response
    {
        $settings = $this->settingsRepository->find(1);
        $form = $this->createForm(SettingsType::class, $settings, [
            'is_admin' => $this->isGranted('ROLE_SUPER_ADMIN')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();
            /** @var UploadedFile|null $faviconFile */
            $faviconFile = $form->get('favicon')->getData();

            // Dossier où stocker les fichiers
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

            if ($logoFile) {
                $newFilename = uniqid('logo_') . '.' . $logoFile->guessExtension();
                $logoFile->move($uploadDir, $newFilename);
                $settings->setLogo('/uploads/' . $newFilename);
            }

            if ($faviconFile) {
                $newFilename = uniqid('favicon_') . '.' . $faviconFile->guessExtension();
                $faviconFile->move($uploadDir, $newFilename);
                $settings->setFavicon('/uploads/' . $newFilename);
            }

            $this->entityManager->flush();
            $this->cacheService->resetSettings();

            $this->addFlash('success', 'Paramètres enregistrés !');
            return $this->redirectToRoute('app_admin_settings');
        }

        return $this->render('admin/settings/index.html.twig', [
            'form' => $form,
        ]);
    }
}

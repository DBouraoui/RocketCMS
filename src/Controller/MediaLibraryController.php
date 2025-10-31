<?php

namespace App\Controller;

use App\Repository\MediaLibraryRepository;
use App\Repository\MenuLinkRepository;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MediaLibraryController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private MediaLibraryRepository $mediaLibraryRepository,
        private MenuLinkRepository $menuLinkRepository,
    ){}

    #[Route('/media-library', name: 'app_media_library_index', methods: ['GET'])]
    public function index(): Response
    {
        $menuLinks = $this->menuLinkRepository->findOneBy(['slug'=>'ma-mediateque']);

        if (!$menuLinks->isActive()) {
            return $this->redirectToRoute('app_home_index');
        }

        $mediaLibrary = $this->mediaLibraryRepository->findAll();

        return $this->render('Themes/'.$this->settingsService->getTheme().'/mediaLibrary/index.html.twig', [
            'mediaLibrary' => $mediaLibrary,
        ]);
    }
}

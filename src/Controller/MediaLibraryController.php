<?php

namespace App\Controller;

use App\Repository\MediaLibraryRepository;
use App\Repository\MenuLinkRepository;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function index(Security $security): Response
    {
        $mediaLibrary = $this->menuLinkRepository->findOneBy(['slug'=>'ma-mediateque']);

        if (!$security->isGranted('view', $mediaLibrary)) {
            return $this->redirectToRoute('app_home_index');
        }

        $mediaLibrary = $this->mediaLibraryRepository->findAll();

        return $this->render('Themes/'.$this->settingsService->getTheme().'/mediaLibrary/index.html.twig', [
            'mediaLibrary' => $mediaLibrary,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MediaLibraryController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService
    ){}

    #[Route('/media-library', name: 'app_media_library_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Themes/'.$this->settingsService->getTheme().'/mediaLibrary/index.html.twig');
    }
}

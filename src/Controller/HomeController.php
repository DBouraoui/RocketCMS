<?php

namespace App\Controller;

use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService
    ){}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('Themes/'.$this->settingsService->getTheme().'/home/index.html.twig');
    }
}

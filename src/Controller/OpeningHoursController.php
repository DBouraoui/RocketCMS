<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MenuLinkRepository;
use App\Repository\OpeningHoursRepository;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OpeningHoursController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private MenuLinkRepository $menuLinkRepository,
        private OpeningHoursRepository $openingHoursRepository,
    ){}
    #[Route('/opening-hours', name: 'app_opening_hours_index', methods: ['GET'])]
    public function index(): Response
    {
        $horraireMenu = $this->menuLinkRepository->findOneBy(['slug'=>'mes-horraires']);

        if (!$horraireMenu->isActive()) {
            return $this->redirectToRoute('app_home_index');
        }

        $openingHours = $this->openingHoursRepository->findAll();

        return $this->render('Themes/'.$this->settingsService->getTheme().'/opening_hours/index.html.twig', [
            'openingHours' => $openingHours,
        ]);
    }
}

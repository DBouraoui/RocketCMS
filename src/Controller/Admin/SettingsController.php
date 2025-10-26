<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\SettingsType;
use App\Repository\SettingsRepository;
use App\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $this->entityManager->flush();
            // todo ajouter pour le logo et le favicon
            $this->cacheService->resetSettings();

            $this->addFlash('success', 'Paramètres enregistrés !');
            return $this->redirectToRoute('app_admin_settings');
        }

        return $this->render('admin/settings/index.html.twig',[
            'form' => $form,
        ]);
    }
}

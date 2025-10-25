<?php

namespace App\Controller;

use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    private const TITLE = "Mon premier site";
    private const DESCRIPTION = "Mon premier site avec une description";

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ){}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    private function createSettings(): Settings {
        $settings = new Settings();
        $settings->setTitle(self::TITLE);
        $settings->setDescription(self::DESCRIPTION);

        $this->entityManager->persist($settings);
        $this->entityManager->flush();

        return $settings;
    }
}

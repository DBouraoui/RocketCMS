<?php

namespace App\Controller;

use App\Form\NewsletterType;
use App\Repository\MenuLinkRepository;
use App\Service\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NewsletterController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private EntityManagerInterface $entityManager,
        private MenuLinkRepository $menuLinkRepository,
    ){}

    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(Request $request): Response
    {
       $newsletterStructure = $this->menuLinkRepository->findOneBy(['slug' => 'newsletter']);

       if (!$newsletterStructure->isActive()) {
           return $this->redirectToRoute('app_home');
       }

        $form = $this->createForm(NewsletterType::class);
        $form->handleRequest($request);

        $honeypot = $request->request->get('newsletter_hp');

        if (!empty($honeypot)) {
            // Si le champ est rempli → bot probable
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_newsletter');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $newsletter = $form->getData();
            $newsletter->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($newsletter);
            $this->entityManager->flush();

            $this->addFlash('success', 'Inscription à la newsletter avec succés');
            return $this->redirectToRoute('app_newsletter');
        }

        return $this->render('Themes/'.$this->settingsService->getTheme().'/newsletter/index.html.twig', [
            'form' => $form,
            'newsletter' => $newsletterStructure->getContent(),
        ]);
    }
}

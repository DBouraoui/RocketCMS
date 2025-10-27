<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\MenuLinkRepository;
use App\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class PageController extends AbstractController
{

    public function __construct(
        private MenuLinkRepository $menuLinkRepository,
        private EntityManagerInterface $entityManager,
        private CacheService $cacheService,
        private CsrfTokenManagerInterface $csrfTokenManager
    ){}
    #[Route('/admin/page', name: 'app_admin_pages')]
    public function index(): Response
    {
        $this->menuLinkRepository->findAll();

        return $this->render('admin/pages/index.html.twig', [
            'pages' => $this->menuLinkRepository->findAll(),
        ]);
    }

    #[Route('/admin/page/{id}/toggle', name: 'app_admin_page_toggle', methods: ['POST'])]
    public function toggleStatusPage(int $id, Request $request): Response
    {
        $token = new CsrfToken('toggle-page-' . $id, $request->request->get('_token'));

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('app_admin_pages');
        }

        try {
            $menuLink = $this->menuLinkRepository->find($id);

            if (!$menuLink) {
                throw new \Exception('Page non trouvée');
            }

            $menuLink->setIsActive(!$menuLink->isActive());
            $menuLink->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->flush();

            $this->addFlash('success', sprintf(
                'La page "%s" a été %s.',
                $menuLink->getTitle(),
                $menuLink->isActive() ? 'activée' : 'désactivée'
            ));

            $this->cacheService->resetMenuLinks();

            return $this->redirectToRoute('app_admin_pages');

        } catch(\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_admin_pages');
        }
    }
}

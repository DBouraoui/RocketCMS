<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\MenuLink;
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
        private CsrfTokenManagerInterface $csrfTokenManager
    ){}
    #[Route('/admin/page', name: 'app_admin_pages_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/pages/index.html.twig', [
            'pages' => $this->menuLinkRepository->findAll(),
            'X-Frame-Options' => 'ALLOWALL',
        ]);
    }

    #[Route('/admin/page/{id}/toggle', name: 'app_admin_page_toggle', methods: ['POST'])]
    public function toggleStatusPage(int $id, Request $request): Response
    {
        $token = new CsrfToken('toggle-page-' . $id, $request->request->get('_token'));

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('app_admin_pages_index');
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

            return $this->redirectToRoute('app_admin_pages_index');

        } catch(\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_admin_pages_index');
        }
    }

    #[Route('/admin/page/{id}/footer', name: 'app_admin_page_update_footer', methods: ['POST'])]
    public function updateFooter(MenuLink $menuLink, Request $request): Response
    {
        if ($this->isCsrfTokenValid('update'.$menuLink->getId(), $request->request->get('_token'))) {

            $menuLink->setIsFooter(!$menuLink->isFooter());

            $this->entityManager->flush();

            $this->addFlash('success', 'Le menu a été modifié.');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_admin_pages_index');
    }

    #[Route('/admin/page/{id}/navbar', name: 'app_admin_page_update_navbar', methods: ['POST'])]
    public function updateNavbar(MenuLink $menuLink, Request $request): Response
    {
        if ($this->isCsrfTokenValid('update'.$menuLink->getId(), $request->request->get('_token'))) {

            $menuLink->setIsNavbar(!$menuLink->isNavbar());

            $this->entityManager->flush();

            $this->addFlash('success', 'Le menu a été modifié.');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_admin_pages_index');
    }

}

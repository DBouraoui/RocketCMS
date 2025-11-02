<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BlogPost;
use App\Event\ContentViewEvent;
use App\Repository\BlogPostRepository;
use App\Repository\MenuLinkRepository;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private BlogPostRepository $blogPostRepository,
        private MenuLinkRepository $menuLinkRepository,
        private EventDispatcherInterface $eventDispatcher
    ){}
    #[Route('/blog',name: 'app_blog_index', methods: ['GET'])]
    public function index(Request $request, Security $security): Response
    {
        $blogPage = $this->menuLinkRepository->findOneBy(['slug'=>'mon-blog']);

        if (!$security->isGranted('view', $blogPage)) {
            return $this->redirectToRoute('app_home_index');
        }

        // Récupérer la query de recherche
        $search = $request->query->get('q', '');

        // Récupérer le tri (date_desc ou date_asc)
        $sort = $request->query->get('sort', 'date_desc');

        $qb = $this->blogPostRepository->createQueryBuilder('b');

        // Filtre par titre, sous-titre ou tags si recherche
        if ($search) {
            $qb->andWhere('b.title LIKE :search OR b.subtitle LIKE :search OR b.tags LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        // Tri
        if ($sort === 'date_asc') {
            $qb->orderBy('b.createdAt', 'ASC');
        } else {
            $qb->orderBy('b.createdAt', 'DESC');
        }

        $blogPosts = $qb->getQuery()->getResult();

        return $this->render('Themes/'.$this->settingsService->getTheme().'/blog/index.html.twig', [
            'blog_posts' => $blogPosts,
        ]);
    }

    #[Route('/blog/{id}', name: 'app_blog_post_show', methods: ['GET'])]
    public function show(BlogPost $blogPost, Request $request): Response
    {
        // Récupération des tags du post courant (stockés en string séparée par des virgules)
        $tags = array_map('trim', explode(',', $blogPost->getTags() ?? ''));

        $qb = $this->blogPostRepository->createQueryBuilder('b')
            ->where('b.id != :id')
            ->setParameter('id', $blogPost->getId())
            ->setMaxResults(3) // on limite à 3 articles similaires
            ->orderBy('b.createdAt', 'DESC');

        // Si le post a des tags, on cherche ceux qui en partagent au moins un
        if (!empty($tags)) {
            $orX = $qb->expr()->orX();
            foreach ($tags as $index => $tag) {
                $orX->add($qb->expr()->like('b.tags', ':tag' . $index));
                $qb->setParameter('tag' . $index, '%' . $tag . '%');
            }
            $qb->andWhere($orX);
        }

        $similaires = $qb->getQuery()->getResult();

        $session = $request->getSession();
        $viewedPosts = $session->get('viewed_posts', []);

        if (!in_array($blogPost->getId(), $viewedPosts, true)) {
            $this->eventDispatcher->dispatch(new ContentViewEvent($blogPost), ContentViewEvent::NAME);
            $viewedPosts[] = $blogPost->getId();
            $session->set('viewed_posts', $viewedPosts);
        }



        return $this->render('Themes/' . $this->settingsService->getTheme() . '/blog/show.html.twig', [
            'post' => $blogPost,
            'similaires' => $similaires,
        ]);
    }


}

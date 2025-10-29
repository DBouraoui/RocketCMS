<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BlogPostRepository;
use App\Service\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    public function __construct(
        private SettingsService $settingsService,
        private BlogPostRepository $blogPostRepository,
    ){}
    #[Route('/blog',name: 'app_blog_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
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

    #[Route('/blog/{id}',name: 'app_blog_post_show', methods: ['GET'])]
    public function show(Request $request)
    {

    }

}

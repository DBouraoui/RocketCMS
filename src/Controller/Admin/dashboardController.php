<?php

namespace App\Controller\Admin;

use App\Repository\BlogPostRepository;
use App\Repository\ContactSubmissionRepository;
use App\Repository\MediaLibraryRepository;
use App\Repository\MenuLinkRepository;
use App\Repository\NewsletterRepository;
use App\Repository\ReminderPhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class dashboardController extends AbstractController
{
    public function __construct(
        private readonly BlogPostRepository $blogPostRepository,
        private readonly MenuLinkRepository $menuLinkRepository,
        private readonly NewsletterRepository $newsletterRepository,
        private readonly ReminderPhoneRepository $reminderPhoneRepository,
        private readonly ContactSubmissionRepository $contactSubmissionRepository,
        private readonly MediaLibraryRepository $mediaLibraryRepository,
        private readonly TagAwareCacheInterface $tagAwareAdapter,
    ){}
    #[Route('/admin/dashboard', name: 'app_admin_dashboard_index', methods: ['GET'])]
    public function index(): Response
    {
        $dashboardData = $this->tagAwareAdapter->get('dashboard', function(ItemInterface $item) {
            // Définir un TTL si tu veux (ex: 5 min)
            $item->expiresAfter(60);
            // Ajouter un tag pour pouvoir invalider facilement
            $item->tag('dashboard');

            //[]['title'=>'titre, 'viewCount'=>123], total=>1221
            $blogs = $this->blogPostRepository->getBlogDashboard();
            $blogs['total'] = count($blogs);

            //array:2 ["active" => 6 "inactive" => 1]
            $pages = $this->menuLinkRepository->getDashboard();

            //count newsletter int
            $newsletter = count($this->newsletterRepository->findAll());

            // count reminderPhone int
            $reminderPhone = count($this->reminderPhoneRepository->findAll());

            //count contact not read int
            $contactSubmission = $this->contactSubmissionRepository->dashboardCountNotRead();

            //count media int
            $mediaLibrary = count($this->mediaLibraryRepository->findAll());
            $totalMediaSize = $this->mediaLibraryRepository->getTotalSize();

            // Retourner toutes les données dans un tableau
            return [
                'blogs' => $blogs,
                'pages' => $pages,
                'newsletter' => $newsletter,
                'reminderPhone' => $reminderPhone,
                'contactSubmission' => $contactSubmission,
                'mediaLibrary' => $mediaLibrary,
                'totalMediaSize' => $totalMediaSize,
            ];
        });

        // Passer les données récupérées depuis le cache au template
        return $this->render('admin/dashboard/index.html.twig', $dashboardData);
    }

}

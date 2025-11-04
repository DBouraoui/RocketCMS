<?php

namespace App\Controller\Admin;

use App\Entity\ContactSubmission;
use App\Repository\BlogPostRepository;
use App\Repository\ContactSubmissionRepository;
use App\Repository\MediaLibraryRepository;
use App\Repository\MenuLinkRepository;
use App\Repository\NewsletterRepository;
use App\Repository\ReminderPhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class dashboardController extends AbstractController
{
    public function __construct(
        private readonly BlogPostRepository $blogPostRepository,
        private readonly MenuLinkRepository $menuLinkRepository,
        private readonly NewsletterRepository $newsletterRepository,
        private readonly ReminderPhoneRepository $reminderPhoneRepository,
        private readonly ContactSubmissionRepository $contactSubmissionRepository,
        private readonly MediaLibraryRepository $mediaLibraryRepository,
    ){}
    #[Route('/admin/dashboard', name: 'app_admin_dashboard_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        //[]['title'=>'titre, 'viewCount'=>123], total=>1221
        $blogs = $this->blogPostRepository->getBlogDashboard();
        $blogs['total'] = count($blogs);

        //array:2 ["active" => 6 "inactive" => 1]
        $pages = $this->menuLinkRepository->getDashboard();

        //count newsletter int
        $newsletter = count($this->newsletterRepository->findAll());

        // count reminderPhone int
        $reminderPhone = count($this->reminderPhoneRepository->findAll());

        //count contat not read int
        $contactSubmission = $this->contactSubmissionRepository->dashboardCountNotRead();

        //count media int
        $mediaLibrary = count($this->mediaLibraryRepository->findAll());

        return $this->render('admin/dashboard/index.html.twig', [
            'blogs' => $blogs,
            'pages' => $pages,
            'newsletter' => $newsletter,
            'reminderPhone' => $reminderPhone,
            'contactSubmission' => $contactSubmission,
            'mediaLibrary'=> $mediaLibrary,
        ]);
    }
}

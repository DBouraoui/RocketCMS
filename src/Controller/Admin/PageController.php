<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/admin/page', name: 'app_admin_pages')]
    public function index(): Response
    {
        return $this->render('admin/pages/index.html.twig');
    }
}

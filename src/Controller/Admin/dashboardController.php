<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class dashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        //todo afficher -> nb de vue moyenne sur les blog, nb de page activer et désactiver, nb de visiteur moyens en semaines,
        // nb de message non lue, nb de rapelle encore actif.
        return $this->render('admin/dashboard/index.html.twig');
    }
}

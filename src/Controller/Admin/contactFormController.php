<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class contactFormController extends AbstractController
{
    #[Route('/admin/contact/form', name: 'app_admin_contact_form')]
    public function index(): Response
    {
        return $this->render('admin/contact_form/index.html.twig', [
            'controller_name' => 'Admin/contactFormController',
        ]);
    }
}

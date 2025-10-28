<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\MenuLink;
use App\Form\DataEditorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageContentEditorController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/admin/page-content-editor/{id}', name: 'admin_page_content_editor_index', methods: ['GET','POST'])]
    public function index(MenuLink $menuLink, Request $request): Response
    {
        // Crée le formulaire dynamique
        $form = $this->createForm(DataEditorType::class, $menuLink);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les valeurs du formulaire et sauvegarde dans content
            $data = $form->getData();

            $content = [];
            foreach ($menuLink->getStructure() as $fieldName => $fieldOptions) {
                $content[$fieldName] = $form->get($fieldName)->getData();
            }

            $menuLink->setContent($content);
            $this->entityManager->persist($menuLink);
            $this->entityManager->flush();

            $this->addFlash('success', 'Page mise à jour avec succès !');

            return $this->redirectToRoute('admin_page_content_editor_index', ['id' => $menuLink->getId()]);
        }

        return $this->render('admin/page_content_editor/index.html.twig', [
            'menuLink' => $menuLink,
            'form' => $form->createView(),
        ]);
    }
}

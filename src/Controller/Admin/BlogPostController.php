<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/blog/post')]
final class BlogPostController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
    ){}

    #[Route(name: 'app_admin_blog_post_index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('admin/blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_blog_post_new', methods: ['GET', 'POST'])]
    public function new(#[CurrentUser]User $user,Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $blogPost->setSlug($this->slugger->slug($blogPost->getTitle()));

            /** @var UploadedFile|null $coverPicture */
            $coverPicture = $form->get('coverPicture')->getData();

            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

            if ($coverPicture) {
                // Génère un nom unique basé sur le titre + un ID unique
                $safeFilename = $this->slugger->slug($blogPost->getTitle());
                $uniqueSuffix = uniqid();
                $extension = $coverPicture->guessExtension();
                $newFilename = $safeFilename . '_' . $uniqueSuffix . '.' . $extension;

                // Déplace le fichier dans le dossier uploads
                $coverPicture->move($uploadDir, $newFilename);

                // Enregistre le chemin relatif
                $blogPost->setCoverPicture('/uploads/' . $newFilename);
            }

            $blogPost->setCreatedAt(new \DateTimeImmutable());

            if (!$blogPost->getAuthor()) {
                $blogPost->setAuthor($user);
            }

            $entityManager->persist($blogPost);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article a bien été créer');
            return $this->redirectToRoute('app_admin_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_blog_post_show', methods: ['GET'])]
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('admin/blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_blog_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre article a bien été modifié');
            return $this->redirectToRoute('app_admin_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_blog_post_delete', methods: ['POST'])]
    public function delete(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Votre article a bien été supprimer');
        return $this->redirectToRoute('app_admin_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}

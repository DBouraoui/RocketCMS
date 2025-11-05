<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
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
        private BlogPostRepository $blogPostRepository,
    ) {}

    #[Route(name: 'app_admin_blog_post_index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('admin/blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_blog_post_new', methods: ['GET', 'POST'])]
    public function new(#[CurrentUser] User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->setSlug($this->slugger->slug($blogPost->getTitle()));

            $isSlugAlreadyExist = $this->blogPostRepository->findOneBy(['slug'=>$blogPost->getSlug()]);

            if ($isSlugAlreadyExist) {
                $form->get('title')->addError(new FormError('Le titre de blog existe déja'));
            }

            if (count($form->getErrors(true)) === 0) {
                /** @var UploadedFile|null $coverPicture */
                $coverPicture = $form->get('coverPicture')->getData();

                if ($coverPicture) {
                    $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    $safeFilename = $this->slugger->slug($blogPost->getTitle());
                    $uniqueSuffix = uniqid();
                    $extension = $coverPicture->guessExtension() ?? 'jpg';
                    $newFilename = $safeFilename . '_' . $uniqueSuffix . '.' . $extension;

                    $coverPicture->move($uploadDir, $newFilename);
                    $blogPost->setCoverPicture('/uploads/' . $newFilename);
                }

                $blogPost->setCreatedAt(new \DateTimeImmutable());
                $blogPost->setAuthor($blogPost->getAuthor() ?? $user);

                $entityManager->persist($blogPost);
                $entityManager->flush();

                $this->addFlash('success', 'Votre article a bien été créé.');
                return $this->redirectToRoute('app_admin_blog_post_index');
            }

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
        $oldImage = $blogPost->getCoverPicture();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Génère un slug à partir du titre
            $slug = $this->slugger->slug($blogPost->getTitle());

            // Vérifie si un autre article utilise déjà ce slug
            $existingPost = $this->blogPostRepository->findOneBy(['slug' => $slug]);

            // Vérifie que ce n'est pas le même article qu'on édite
            if ($existingPost && $existingPost->getId() !== $blogPost->getId()) {
                // Ajoute une erreur sur le champ "title"
                $form->get('title')->addError(new FormError('Ce titre existe déjà.'));

                // On renvoie la vue sans redirection → les données restent affichées
                return $this->render('admin/blog_post/edit.html.twig', [
                    'blog_post' => $blogPost,
                    'form' => $form,
                ]);
            }

            /** @var UploadedFile|null $newImage */
            $newImage = $form->get('coverPicture')->getData();
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';

            // Si une nouvelle image est uploadée
            if ($newImage) {
                // Supprime l’ancienne image si elle existe
                if ($oldImage && file_exists($this->getParameter('kernel.project_dir') . '/public' . $oldImage)) {
                    @unlink($this->getParameter('kernel.project_dir') . '/public' . $oldImage);
                }

                // Génère un nom de fichier unique
                $safeFilename = $slug;
                $uniqueSuffix = uniqid();
                $extension = $newImage->guessExtension() ?? 'jpg';
                $newFilename = $safeFilename . '_' . $uniqueSuffix . '.' . $extension;

                // Déplace le fichier
                $newImage->move($uploadDir, $newFilename);
                $blogPost->setCoverPicture('/uploads/' . $newFilename);
            } else {
                // Si pas de nouvelle image → on garde l’ancienne
                $blogPost->setCoverPicture($oldImage);
            }

            // Met à jour la date de modification
            $blogPost->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Votre article a bien été modifié.');
            return $this->redirectToRoute('app_admin_blog_post_index');
        }

        return $this->render('admin/blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_blog_post_delete', methods: ['POST'])]
    public function delete(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->getPayload()->getString('_token'))) {
            // Supprime l'image du disque
            if ($blogPost->getCoverPicture() && file_exists($this->getParameter('kernel.project_dir') . '/public' . $blogPost->getCoverPicture())) {
                @unlink($this->getParameter('kernel.project_dir') . '/public' . $blogPost->getCoverPicture());
            }

            $entityManager->remove($blogPost);
            $entityManager->flush();

            $this->addFlash('success', 'Votre article a bien été supprimé.');
        }

        return $this->redirectToRoute('app_admin_blog_post_index');
    }
}


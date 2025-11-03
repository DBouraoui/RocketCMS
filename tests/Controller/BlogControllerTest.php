<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Tests\WebTestCase;

class BlogControllerTest extends WebTestCase
{

    /**
     * Crée un article de blog pour les tests
     */
    private function createBlogPost(array $data = []): BlogPost
    {
        if (!empty($data['author'])) {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $data['author']]);
        }

        $blogPost = new BlogPost();
        $blogPost->setTitle($data['title'] ?? 'Article de test');
        $blogPost->setSlug($data['slug'] ?? 'article-test');
        $blogPost->setDescription($data['description'] ?? 'Article de test');
        $blogPost->setAuthor($user ?? null);
        $blogPost->setSubtitle($data['subtitle'] ?? 'Sous-titre de test');
        $blogPost->setContent($data['content'] ?? 'Contenu de test');
        $blogPost->setTags($data['tags'] ?? 'symfony, php, test');
        $blogPost->setCreatedAt($data['createdAt'] ?? new \DateTimeImmutable());

        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        return $blogPost;
    }

    /**
     * Test de la page d'index du blog
     */
    public function testInsertBlogPostSuccess(): void
    {

        // Créer quelques articles
        $this->createBlogPost(['title' => 'Premier article',
            'description' => 'Premier article description',
            'author'=> '1',
            'slug' => 'premier article-test',
            'subtitle' => ' premier Article test',
            'content'=>'je suis un premier articles',
            'tags' => 'symfony, php, test',
            'createdAt' => new \DateTimeImmutable()
        ]);
        $this->createBlogPost(['title' => 'Deuxieme article',
            'slug' => 'deuxieme article-test',
            'description' => 'second article description',
            'author'=> '1',
            'subtitle' => ' deuxieme Article test',
            'content'=>'je suis un deuxieme articles',
            'tags' => 'symfony, php, test',
            'createdAt' => new \DateTimeImmutable()
        ]);

        $this->client->request('GET', '/blog');

        $this->assertResponseIsSuccessful();
    }

}

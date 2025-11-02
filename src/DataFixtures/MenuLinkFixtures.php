<?php

namespace App\DataFixtures;

use App\Entity\MenuLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'id' => 1,
                'title' => 'Connexion',
                'url' => 'app_login',
                'is_active' => true,
                'slug' => 'connexion',
                'updated_at' => new \DateTimeImmutable('2025-10-28 17:30:08'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => null,
                'content' => null,
            ],
            [
                'id' => 3,
                'title' => 'Contact',
                'url' => 'app_contact_index',
                'is_active' => true,
                'slug' => 'contact',
                'updated_at' => new \DateTimeImmutable('2025-10-30 10:28:10'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => [
                    "title" => ["champ" => "text", "label" => "Titre principal", "helper" => "Le titre affiché en haut du formulaire de contact"],
                    "subtitle" => ["champ" => "textarea", "label" => "Sous-titre", "helper" => "Le texte qui accompagne le titre"],
                    "button_label" => ["champ" => "text", "label" => "Texte du bouton", "helper" => "Le texte du bouton d’envoie du formulaire"],
                ],
                'content' => [
                    "title" => "Une question, un problème contactez nous !",
                    "subtitle" => "Pour tout problème ou sujet divers n'hésiter pas a nous contacter",
                    "button_label" => "Soumettre le formulaire !",
                ],
            ],
            [
                'id' => 6,
                'title' => 'Newsletter',
                'url' => 'app_newsletter_index',
                'is_active' => true,
                'slug' => 'newsletter',
                'updated_at' => new \DateTimeImmutable('2025-10-28 17:30:08'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => [
                    "title" => ["champ" => "text", "label" => "Titre principal", "helper" => "Le titre affiché en haut de la newsletter"],
                    "subtitle" => ["champ" => "textarea", "label" => "Sous-titre", "helper" => "Le texte qui accompagne le titre"],
                    "button_label" => ["champ" => "text", "label" => "Texte du bouton", "helper" => "Le texte du bouton d’inscription"],
                ],
                'content' => [
                    "title" => "Restons en contact !",
                    "subtitle" => "Profitez d'avantage spéciaux et en avance !",
                    "button_label" => "M'inscrire a la newsletter",
                ],
            ],
            [
                'id' => 7,
                'title' => 'Rappel téléphonique',
                'url' => 'app_reminder_phone_index',
                'is_active' => true,
                'slug' => 'reminder-phone',
                'updated_at' => new \DateTimeImmutable('2025-10-28 17:30:12'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => [
                    "title" => ["champ" => "text", "label" => "Titre principal", "helper" => "Le titre affiché en haut du formulaire de contact"],
                    "subtitle" => ["champ" => "textarea", "label" => "Sous-titre", "helper" => "Le texte qui accompagne le titre"],
                    "button_label" => ["champ" => "text", "label" => "Texte du bouton", "helper" => "Le texte du bouton d’envoie du formulaire"],
                ],
                'content' => [],
            ],
            [
                'id' => 8,
                'title' => 'Mon blog',
                'url' => 'app_blog_index',
                'is_active' => true,
                'slug' => 'mon-blog',
                'updated_at' => new \DateTimeImmutable('2025-10-29 16:57:31'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => [],
                'content' => [],
            ],
            [
                'id' => 9,
                'title' => 'Médiateque',
                'url' => 'app_media_library_index',
                'is_active' => true,
                'slug' => 'ma-mediateque',
                'updated_at' => new \DateTimeImmutable('2025-10-30 10:21:13'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => [],
                'content' => [],
            ],
            [
                'id' => 10,
                'title' => 'Nos horraires',
                'url' => 'app_opening_hours_index',
                'is_active' => true,
                'slug' => 'mes-horraires',
                'updated_at' => new \DateTimeImmutable('2025-10-30 10:21:13'),
                'is_footer' => true,
                'is_navbar' => true,
                'structure' => [],
                'content' => [],
            ],
        ];

        foreach ($data as $item) {
            $menu = new MenuLink();
            $menu->setTitle($item['title']);
            $menu->setUrl($item['url']);
            $menu->setIsActive($item['is_active']);
            $menu->setSlug($item['slug']);
            $menu->setUpdatedAt($item['updated_at']);
            $menu->setIsFooter($item['is_footer']);
            $menu->setIsNavbar($item['is_navbar']);
            $menu->setStructure($item['structure']);
            $menu->setContent($item['content']);
            $manager->persist($menu);
        }

        $manager->flush();
    }
}

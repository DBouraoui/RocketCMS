<?php

namespace App\Service;

use App\Entity\MenuLink;
use App\Entity\Settings;
use App\Repository\MenuLinkRepository;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{
    const int ONE_DAY_CACHE = 24 * 3600;
    const int ONE_MONTH_CACHE = (24 * 3600) * 30;
    public function __construct(
        private MenuLinkRepository $menuLinkRepository,
        private TagAwareCacheInterface $cache,
        private SettingsRepository $settingsRepository,
        private EntityManagerInterface $entityManager
    ){}

    public function getMenuLinks(): array
    {

      return  $this->cache->get('navbar_links', function (ItemInterface $item) {
            $item->expiresAfter(self::ONE_DAY_CACHE);

            return $this->menuLinkRepository->findBy(['isActive' => true]);
        });
    }

    public function getSettings(): Settings
    {
       return $this->cache->get('settings', function (ItemInterface $item) {
            $item->expiresAfter(self::ONE_DAY_CACHE);

            $settings = $this->settingsRepository->find(1);

            if (!$settings) {
                $settings = new Settings();
                $settings->setTitle("Mon nouveau site");
                $settings->setDescription("Bonjour voici mon nouveau site web !");
                $this->entityManager->persist($settings);
                $this->entityManager->flush();
            }

            return $settings;
        });
    }
}

<?php
namespace App\Twig;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private const TITLE = "Mon premier site";
    private const DESCRIPTION = "Mon premier site avec une description";
    public function __construct(
        private SettingsRepository $settingsRepository,
        private EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $cache,
    ) {}

    public function getGlobals(): array
    {
        $settings = $this->cache->get('settings', function (ItemInterface $item) {
            $item->expiresAfter(7200);

            $settings = $this->settingsRepository->find(1);

            if (!$settings) {
                $settings = new Settings();
                $settings->setTitle(self::TITLE);
                $settings->setDescription(self::DESCRIPTION);
                $this->entityManager->persist($settings);
                $this->entityManager->flush();
            }

            return $settings;
        });

        return [
            'settings' => $settings
        ];
    }

    private function createSettings() {
        $settings = new Settings();
        $settings->setTitle(self::TITLE);
        $settings->setDescription(self::DESCRIPTION);

        $this->entityManager->persist($settings);
        $this->entityManager->flush();
    }
}

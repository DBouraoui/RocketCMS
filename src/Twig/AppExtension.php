<?php
namespace App\Twig;

use App\Entity\Settings;
use App\Repository\MenuLinkRepository;
use App\Repository\SettingsRepository;
use App\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private CacheService $cacheService,
        private MenuLinkRepository $menuLinkRepository,
    ) {}

    public function getGlobals(): array
    {
        $settings = $this->cacheService->getSettings();
        $links = $this->menuLinkRepository->findAll();
        $date = new \DateTime();

        return [
            'settings' => $settings,
            'links' => $links,
            'date' => $date,
        ];
    }
}

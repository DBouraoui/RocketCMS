<?php

namespace App\Service;


class SettingsService
{
    public function __construct(
        private CacheService $cacheService,
    ){}
    public function getTheme(): string
    {
        try {
            $settings = $this->cacheService->getSettings();

            return $settings->getTheme()->value;

        } catch(\Exception $e) {
            throw new \RuntimeException('Unable to get settings.');
        }
    }
}

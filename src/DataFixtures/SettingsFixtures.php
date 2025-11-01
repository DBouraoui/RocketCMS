<?php

namespace App\DataFixtures;

use App\Entity\Settings;
use App\Enum\ThemesEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingsFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
        $settings = new Settings();

        $settings->setTitle('MyRocket')
            ->setDescription('Un site web propulser par MyRocket')
            ->setContactEmail('admin@gmail.com')
            ->setContactPhone('0673748394')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setTheme(ThemesEnum::BUSINESS);
    }
}

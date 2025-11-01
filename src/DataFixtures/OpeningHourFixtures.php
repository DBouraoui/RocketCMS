<?php

namespace App\DataFixtures;

use App\Entity\OpeningHours;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OpeningHourFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $days = [
            'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'
        ];

        foreach ($days as $day) {
            $hour = new OpeningHours();
            $hour->setDay($day);
            $hour->setIsclosed($day === 'dimanche');
            $hour->setUpdatedAt(new \DateTimeImmutable('2025-10-28 17:30:08'));
            $manager->persist($hour);
        }

        $manager->flush();
    }
}

<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    protected $client;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }

    private function resetDatabase(): void
    {
        $connection = $this->entityManager->getConnection();
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (empty($metadata)) {
            throw new \Exception('No metadata found to create schema.');
        }

        $platform = $connection->getDatabasePlatform();

        // Désactivation des contraintes (compatible MySQL, PostgreSQL, SQLite)
        if ($platform->getName() === 'mysql') {
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        } elseif ($platform->getName() === 'sqlite') {
            $connection->executeStatement('PRAGMA foreign_keys = OFF');
        }

        // Supprimer le schéma puis le recréer
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        // Réactivation des contraintes
        if ($platform->getName() === 'mysql') {
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        } elseif ($platform->getName() === 'sqlite') {
            $connection->executeStatement('PRAGMA foreign_keys = ON');
        }
    }

}

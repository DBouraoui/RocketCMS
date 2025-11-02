<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101163809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opening_hours CHANGE open_morning open_morning VARCHAR(8) DEFAULT NULL, CHANGE close_morning close_morning VARCHAR(8) DEFAULT NULL, CHANGE open_afternoon open_afternoon VARCHAR(8) DEFAULT NULL, CHANGE close_afternoon close_afternoon VARCHAR(8) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opening_hours CHANGE open_morning open_morning VARCHAR(5) DEFAULT NULL, CHANGE close_morning close_morning VARCHAR(5) DEFAULT NULL, CHANGE open_afternoon open_afternoon VARCHAR(5) DEFAULT NULL, CHANGE close_afternoon close_afternoon VARCHAR(5) DEFAULT NULL');
    }
}

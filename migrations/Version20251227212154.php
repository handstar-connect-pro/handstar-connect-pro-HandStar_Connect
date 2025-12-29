<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227212154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE mental_trainer ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE physical_trainer ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE physio_therapist ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE player ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE referee ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE technical_director ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE video_analyst ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE mental_trainer DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE physical_trainer DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE physio_therapist DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE player DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE referee DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE technical_director DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE video_analyst DROP created_at, DROP updated_at');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227205817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technical_director ADD photo VARCHAR(255) DEFAULT NULL, ADD gender VARCHAR(20) NOT NULL, ADD birth_date DATE DEFAULT NULL, ADD certifications VARCHAR(50) NOT NULL, ADD division VARCHAR(50) NOT NULL, ADD nationality VARCHAR(100) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, ADD region VARCHAR(50) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD is_profile_public TINYINT NOT NULL, ADD profil VARCHAR(50) NOT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE technical_director ADD CONSTRAINT FK_A29A1C4FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A29A1C4FA76ED395 ON technical_director (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technical_director DROP FOREIGN KEY FK_A29A1C4FA76ED395');
        $this->addSql('DROP INDEX UNIQ_A29A1C4FA76ED395 ON technical_director');
        $this->addSql('ALTER TABLE technical_director DROP photo, DROP gender, DROP birth_date, DROP certifications, DROP division, DROP nationality, DROP address, DROP postal_code, DROP city, DROP region, DROP phone, DROP is_profile_public, DROP profil, DROP user_id');
    }
}

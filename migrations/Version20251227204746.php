<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227204746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club ADD club_number VARCHAR(50) NOT NULL, ADD logo VARCHAR(255) DEFAULT NULL, ADD contact_position VARCHAR(50) NOT NULL, ADD phone VARCHAR(20) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD postal_code VARCHAR(10) NOT NULL, ADD city VARCHAR(100) NOT NULL, ADD region VARCHAR(50) NOT NULL, ADD division VARCHAR(50) NOT NULL, ADD profil VARCHAR(50) NOT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8EE3872B35FA3DE ON club (club_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8EE3872A76ED395 ON club (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872A76ED395');
        $this->addSql('DROP INDEX UNIQ_B8EE3872B35FA3DE ON club');
        $this->addSql('DROP INDEX UNIQ_B8EE3872A76ED395 ON club');
        $this->addSql('ALTER TABLE club DROP club_number, DROP logo, DROP contact_position, DROP phone, DROP address, DROP postal_code, DROP city, DROP region, DROP division, DROP profil, DROP user_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229031315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add properties to Announcement entity and create relations with AnnouncementResponse and SavedAnnouncement';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // 1. Renommer title en offer_title
        $this->addSql('ALTER TABLE announcement CHANGE title offer_title VARCHAR(255) NOT NULL');

        // 2. Ajouter les nouvelles colonnes pour Announcement
        $this->addSql('ALTER TABLE announcement ADD offer_type VARCHAR(255) NOT NULL, ADD offer_description LONGTEXT NOT NULL, ADD offer_user_profil VARCHAR(255) NOT NULL, ADD position_sought VARCHAR(255) NOT NULL, ADD league_concerned VARCHAR(255) NOT NULL, ADD location VARCHAR(255) NOT NULL, ADD offer_status VARCHAR(255) NOT NULL, ADD view_count INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD profil VARCHAR(255) NOT NULL, ADD expires_at DATETIME NOT NULL');

        // 3. Ajouter les relations pour AnnouncementResponse
        $this->addSql('ALTER TABLE announcement_response ADD announcement_id INT NOT NULL');
        $this->addSql('ALTER TABLE announcement_response ADD CONSTRAINT FK_45368108913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
        $this->addSql('CREATE INDEX IDX_45368108913AEA17 ON announcement_response (announcement_id)');

        // 4. Ajouter les relations pour SavedAnnouncement
        $this->addSql('ALTER TABLE saved_announcement ADD announcement_id INT NOT NULL');
        $this->addSql('ALTER TABLE saved_announcement ADD CONSTRAINT FK_C01FC6BA913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
        $this->addSql('CREATE INDEX IDX_C01FC6BA913AEA17 ON saved_announcement (announcement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // 1. Supprimer les relations pour SavedAnnouncement
        $this->addSql('ALTER TABLE saved_announcement DROP FOREIGN KEY FK_C01FC6BA913AEA17');
        $this->addSql('DROP INDEX IDX_C01FC6BA913AEA17 ON saved_announcement');
        $this->addSql('ALTER TABLE saved_announcement DROP announcement_id');

        // 2. Supprimer les relations pour AnnouncementResponse
        $this->addSql('ALTER TABLE announcement_response DROP FOREIGN KEY FK_45368108913AEA17');
        $this->addSql('DROP INDEX IDX_45368108913AEA17 ON announcement_response');
        $this->addSql('ALTER TABLE announcement_response DROP announcement_id');

        // 3. Supprimer les nouvelles colonnes de Announcement
        $this->addSql('ALTER TABLE announcement DROP offer_type, DROP offer_description, DROP offer_user_profil, DROP position_sought, DROP league_concerned, DROP location, DROP offer_status, DROP view_count, DROP created_at, DROP updated_at, DROP profil, DROP expires_at');

        // 4. Renommer offer_title en title
        $this->addSql('ALTER TABLE announcement CHANGE offer_title title VARCHAR(255) NOT NULL');
    }
}

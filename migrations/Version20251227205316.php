<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227205316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player ADD photo VARCHAR(255) DEFAULT NULL, ADD gender VARCHAR(20) NOT NULL, ADD birth_date DATE DEFAULT NULL, ADD height INT DEFAULT NULL, ADD weight INT DEFAULT NULL, ADD handedness VARCHAR(20) NOT NULL, ADD game_position VARCHAR(20) NOT NULL, ADD division VARCHAR(50) NOT NULL, ADD nationality VARCHAR(100) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, ADD region VARCHAR(50) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD is_profile_public TINYINT NOT NULL, ADD profil VARCHAR(50) NOT NULL, ADD current_club_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65CB148FB7 FOREIGN KEY (current_club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_98197A65CB148FB7 ON player (current_club_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65A76ED395 ON player (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65CB148FB7');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65A76ED395');
        $this->addSql('DROP INDEX IDX_98197A65CB148FB7 ON player');
        $this->addSql('DROP INDEX UNIQ_98197A65A76ED395 ON player');
        $this->addSql('ALTER TABLE player DROP photo, DROP gender, DROP birth_date, DROP height, DROP weight, DROP handedness, DROP game_position, DROP division, DROP nationality, DROP address, DROP postal_code, DROP city, DROP region, DROP phone, DROP is_profile_public, DROP profil, DROP current_club_id, DROP user_id');
    }
}

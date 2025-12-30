<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229212345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create notification table for Notification entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            message LONGTEXT NOT NULL,
            type VARCHAR(50) NOT NULL,
            is_read TINYINT(1) DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            read_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            action_url VARCHAR(255) DEFAULT NULL,
            action_label VARCHAR(100) DEFAULT NULL,
            metadata JSON DEFAULT NULL,
            INDEX IDX_BF5476CAA76ED395 (user_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // Create index for faster queries by type and read status
        $this->addSql('CREATE INDEX IDX_NOTIFICATION_TYPE ON notification (type)');
        $this->addSql('CREATE INDEX IDX_NOTIFICATION_READ_STATUS ON notification (is_read)');
        $this->addSql('CREATE INDEX IDX_NOTIFICATION_CREATED_AT ON notification (created_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('DROP INDEX IDX_NOTIFICATION_TYPE ON notification');
        $this->addSql('DROP INDEX IDX_NOTIFICATION_READ_STATUS ON notification');
        $this->addSql('DROP INDEX IDX_NOTIFICATION_CREATED_AT ON notification');
        $this->addSql('DROP TABLE notification');
    }
}

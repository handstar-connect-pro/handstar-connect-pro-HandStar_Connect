<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229211502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Complete AnnouncementResponse entity with message, status, user relation and timestamps';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // 1. Add new columns
        $this->addSql('ALTER TABLE announcement_response ADD message LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE announcement_response ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE announcement_response ADD attachment_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE announcement_response ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE announcement_response ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE announcement_response ADD user_id INT NOT NULL');

        // 2. Update existing column (is_read should have default value)
        $this->addSql('ALTER TABLE announcement_response CHANGE is_read is_read TINYINT(1) DEFAULT 0 NOT NULL');

        // 3. Add foreign key constraint for user_id
        $this->addSql('ALTER TABLE announcement_response ADD CONSTRAINT FK_45368108A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // 4. Create index for user_id
        $this->addSql('CREATE INDEX IDX_45368108A76ED395 ON announcement_response (user_id)');

        // 5. Update existing foreign key to add ON DELETE CASCADE
        $this->addSql('ALTER TABLE announcement_response DROP FOREIGN KEY FK_45368108913AEA17');
        $this->addSql('ALTER TABLE announcement_response ADD CONSTRAINT FK_45368108913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE');

        // 6. Add unique constraint to prevent duplicate responses from same user to same announcement
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_ANNOUNCEMENT_RESPONSE ON announcement_response (user_id, announcement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // 1. Remove unique constraint
        $this->addSql('DROP INDEX UNIQ_USER_ANNOUNCEMENT_RESPONSE ON announcement_response');

        // 2. Remove foreign key for user_id
        $this->addSql('ALTER TABLE announcement_response DROP FOREIGN KEY FK_45368108A76ED395');

        // 3. Drop index for user_id
        $this->addSql('DROP INDEX IDX_45368108A76ED395 ON announcement_response');

        // 4. Remove new columns
        $this->addSql('ALTER TABLE announcement_response DROP message');
        $this->addSql('ALTER TABLE announcement_response DROP status');
        $this->addSql('ALTER TABLE announcement_response DROP attachment_path');
        $this->addSql('ALTER TABLE announcement_response DROP created_at');
        $this->addSql('ALTER TABLE announcement_response DROP updated_at');
        $this->addSql('ALTER TABLE announcement_response DROP user_id');

        // 5. Revert is_read column
        $this->addSql('ALTER TABLE announcement_response CHANGE is_read is_read TINYINT(1) NOT NULL');

        // 6. Revert foreign key to without ON DELETE CASCADE
        $this->addSql('ALTER TABLE announcement_response DROP FOREIGN KEY FK_45368108913AEA17');
        $this->addSql('ALTER TABLE announcement_response ADD CONSTRAINT FK_45368108913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
    }
}

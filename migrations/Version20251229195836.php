<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251229195836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Complete SavedAnnouncement entity with user relation, unique constraint and timestamps';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // 1. Make notes column nullable
        $this->addSql('ALTER TABLE saved_announcement CHANGE notes notes VARCHAR(255) DEFAULT NULL');

        // 2. Add user_id column
        $this->addSql('ALTER TABLE saved_announcement ADD user_id INT NOT NULL');

        // 3. Add created_at column
        $this->addSql('ALTER TABLE saved_announcement ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        // 4. Add foreign key constraint for user_id
        $this->addSql('ALTER TABLE saved_announcement ADD CONSTRAINT FK_C01FC6BAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');

        // 5. Create index for user_id
        $this->addSql('CREATE INDEX IDX_C01FC6BAA76ED395 ON saved_announcement (user_id)');

        // 6. Add unique constraint for (user_id, announcement_id)
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_ANNOUNCEMENT ON saved_announcement (user_id, announcement_id)');

        // 7. Update existing foreign key to add ON DELETE CASCADE
        $this->addSql('ALTER TABLE saved_announcement DROP FOREIGN KEY FK_C01FC6BA913AEA17');
        $this->addSql('ALTER TABLE saved_announcement ADD CONSTRAINT FK_C01FC6BA913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // 1. Remove unique constraint
        $this->addSql('DROP INDEX UNIQ_USER_ANNOUNCEMENT ON saved_announcement');

        // 2. Remove foreign key for user_id
        $this->addSql('ALTER TABLE saved_announcement DROP FOREIGN KEY FK_C01FC6BAA76ED395');

        // 3. Drop index for user_id
        $this->addSql('DROP INDEX IDX_C01FC6BAA76ED395 ON saved_announcement');

        // 4. Remove user_id column
        $this->addSql('ALTER TABLE saved_announcement DROP user_id');

        // 5. Remove created_at column
        $this->addSql('ALTER TABLE saved_announcement DROP created_at');

        // 6. Revert notes column to NOT NULL
        $this->addSql('ALTER TABLE saved_announcement CHANGE notes notes VARCHAR(255) NOT NULL');

        // 7. Revert foreign key to without ON DELETE CASCADE
        $this->addSql('ALTER TABLE saved_announcement DROP FOREIGN KEY FK_C01FC6BA913AEA17');
        $this->addSql('ALTER TABLE saved_announcement ADD CONSTRAINT FK_C01FC6BA913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
    }
}

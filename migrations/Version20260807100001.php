<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807100001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactor ProjectTag to reference Tag entity instead of tag_slug';
    }

    public function up(Schema $schema): void
    {
        // Add new tag_id column
        $this->addSql('ALTER TABLE project_tag ADD tag_id INT NOT NULL');

        // Migrate data: map tag_slug to tag_id
        $this->addSql('UPDATE project_tag pt SET tag_id = (SELECT t.id FROM tag t WHERE t.slug = pt.tag_slug)');

        // Add foreign key constraint
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_PROJECT_TAG_ID FOREIGN KEY (tag_id) REFERENCES tag (id)');

        // Drop old unique constraint
        $this->addSql('DROP INDEX UNIQ_PROJECT_TAG ON project_tag');

        // Drop tag_slug column
        $this->addSql('ALTER TABLE project_tag DROP COLUMN tag_slug');

        // Create new unique constraint
        $this->addSql('ALTER TABLE project_tag ADD UNIQUE INDEX UNIQ_PROJECT_TAG (project_id, tag_id)');
    }

    public function down(Schema $schema): void
    {
        // Add tag_slug column back
        $this->addSql('ALTER TABLE project_tag ADD tag_slug VARCHAR(255) NOT NULL');

        // Migrate data back: map tag_id to tag_slug
        $this->addSql('UPDATE project_tag pt SET tag_slug = (SELECT t.slug FROM tag t WHERE t.id = pt.tag_id)');

        // Drop unique constraint on (project_id, tag_id)
        $this->addSql('DROP INDEX UNIQ_PROJECT_TAG ON project_tag');

        // Drop foreign key
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_PROJECT_TAG_ID');

        // Drop tag_id column
        $this->addSql('ALTER TABLE project_tag DROP COLUMN tag_id');

        // Recreate old unique constraint
        $this->addSql('ALTER TABLE project_tag ADD UNIQUE INDEX UNIQ_PROJECT_TAG (project_id, tag_slug)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807100003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert ProjectTag entity to simple ManyToMany join table';
    }

    public function up(Schema $schema): void
    {
        // Rename old table to temp
        $this->addSql('RENAME TABLE project_tag TO project_tag_old');

        // Create new simple join table
        $this->addSql('CREATE TABLE project_tag (project_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(project_id, tag_id), INDEX IDX_PROJECT_TAG (tag_id))');

        // Migrate data from old to new
        $this->addSql('INSERT INTO project_tag (project_id, tag_id) SELECT project_id, tag_id FROM project_tag_old');

        // Add foreign keys
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_PROJECT_ID FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_TAG_ID FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');

        // Drop old table
        $this->addSql('DROP TABLE project_tag_old');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_PROJECT_ID');
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_TAG_ID');
        $this->addSql('DROP TABLE project_tag');

        // This migration is difficult to reverse; a full database restore is recommended
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807100002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add file_extension and uploaded_at to ProjectImage table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_image ADD file_extension VARCHAR(10) DEFAULT NULL, ADD uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\' DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_image DROP COLUMN file_extension, DROP COLUMN uploaded_at');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260805120100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add language-specific fields to Tool and ProjectImage tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tool ADD name_bg VARCHAR(255) DEFAULT NULL, ADD name_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tool DROP COLUMN name');

        $this->addSql('ALTER TABLE project_image ADD title_bg VARCHAR(255) DEFAULT NULL, ADD title_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_image DROP COLUMN title');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tool ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tool DROP COLUMN name_bg, DROP COLUMN name_en');

        $this->addSql('ALTER TABLE project_image ADD title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_image DROP COLUMN title_bg, DROP COLUMN title_en');
    }
}

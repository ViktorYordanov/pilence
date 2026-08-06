<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260805120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create project, tool, project_image, and project_tag tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE tool (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                icon VARCHAR(255),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE project (
                id INT AUTO_INCREMENT NOT NULL,
                title_bg VARCHAR(255) NOT NULL,
                title_en VARCHAR(255) NOT NULL,
                description_bg LONGTEXT NOT NULL,
                description_en LONGTEXT NOT NULL,
                year SMALLINT NOT NULL,
                cover_image VARCHAR(255),
                created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE project_tag (
                id INT AUTO_INCREMENT NOT NULL,
                project_id INT NOT NULL,
                tag_slug VARCHAR(255) NOT NULL,
                UNIQUE INDEX UNIQ_PROJECT_TAG (project_id, tag_slug),
                INDEX IDX_167FF6EB166D1F9C (project_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE project_image (
                id INT AUTO_INCREMENT NOT NULL,
                project_id INT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                title VARCHAR(255),
                position INT NOT NULL,
                is_visible TINYINT(1) NOT NULL,
                INDEX IDX_E4A33D3C166D1F9C (project_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TABLE project_tool (
                project_id INT NOT NULL,
                tool_id INT NOT NULL,
                INDEX IDX_5F96D11A166D1F9C (project_id),
                INDEX IDX_5F96D11A8F7B22E1 (tool_id),
                PRIMARY KEY(project_id, tool_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE project_tag
            ADD CONSTRAINT FK_167FF6EB166D1F9C FOREIGN KEY (project_id)
            REFERENCES project (id)
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE project_image
            ADD CONSTRAINT FK_E4A33D3C166D1F9C FOREIGN KEY (project_id)
            REFERENCES project (id)
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE project_tool
            ADD CONSTRAINT FK_5F96D11A166D1F9C FOREIGN KEY (project_id)
            REFERENCES project (id),
            ADD CONSTRAINT FK_5F96D11A8F7B22E1 FOREIGN KEY (tool_id)
            REFERENCES tool (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_tool DROP FOREIGN KEY FK_5F96D11A166D1F9C');
        $this->addSql('ALTER TABLE project_tool DROP FOREIGN KEY FK_5F96D11A8F7B22E1');
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_167FF6EB166D1F9C');
        $this->addSql('ALTER TABLE project_image DROP FOREIGN KEY FK_E4A33D3C166D1F9C');
        $this->addSql('DROP TABLE project_tool');
        $this->addSql('DROP TABLE project_image');
        $this->addSql('DROP TABLE project_tag');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE tool');
    }
}

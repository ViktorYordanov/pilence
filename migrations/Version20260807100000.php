<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260807100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Tag entity and seed initial tags';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name_bg VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_389B783989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Seed initial tags
        $this->addSql("INSERT INTO tag (slug, name_bg, name_en, created_at) VALUES ('logo_design', 'Дизайн логотип', 'Logo design', NOW())");
        $this->addSql("INSERT INTO tag (slug, name_bg, name_en, created_at) VALUES ('brand_identity', 'Фирмен стил', 'Brand identity', NOW())");
        $this->addSql("INSERT INTO tag (slug, name_bg, name_en, created_at) VALUES ('print_material', 'Печатни материали', 'Print material', NOW())");
        $this->addSql("INSERT INTO tag (slug, name_bg, name_en, created_at) VALUES ('social_media', 'Социални мрежи', 'Social media', NOW())");
        $this->addSql("INSERT INTO tag (slug, name_bg, name_en, created_at) VALUES ('product_design', 'Дизайн продукт', 'Product design', NOW())");
        $this->addSql("INSERT INTO tag (slug, name_bg, name_en, created_at) VALUES ('digital_illustration', 'Цифрова илюстрация', 'Digital illustration', NOW())");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tag');
    }
}

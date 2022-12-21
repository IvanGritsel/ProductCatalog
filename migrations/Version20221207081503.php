<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207081503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, description LONGTEXT DEFAULT NULL, manufacturer VARCHAR(45) NOT NULL, release_date DATE NOT NULL, price_byn INT NOT NULL, product_type INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_service (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, service_id INT NOT NULL, price INT NOT NULL, term INT NOT NULL, INDEX IDX_304481624584665A (product_id), INDEX IDX_30448162ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_service ADD CONSTRAINT FK_304481624584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_service ADD CONSTRAINT FK_30448162ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_service DROP FOREIGN KEY FK_304481624584665A');
        $this->addSql('ALTER TABLE product_service DROP FOREIGN KEY FK_30448162ED5CA9E6');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_service');
        $this->addSql('DROP TABLE service');
    }
}

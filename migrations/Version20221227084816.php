<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227084816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency_conversions (id INT AUTO_INCREMENT NOT NULL, rates JSON NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO user (id, email, roles, password) VALUE (1, 'mail@mail.com', " . json_encode(["ROLE_ADMIN"]) . ", '$2y$13\$pHfF.woqzMCnguTSuHqD2.tVVnNHuGRUXfUhqyIFX.uUFqEc41D02')"); // Ensure at least 1 Admin user exists
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE currency_conversions');
    }
}

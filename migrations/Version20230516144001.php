<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516144001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits ADD id_categ_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CB8CCB787 FOREIGN KEY (id_categ_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CB8CCB787 ON produits (id_categ_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CB8CCB787');
        $this->addSql('DROP INDEX IDX_BE2DDF8CB8CCB787 ON produits');
        $this->addSql('ALTER TABLE produits DROP id_categ_id');
    }
}

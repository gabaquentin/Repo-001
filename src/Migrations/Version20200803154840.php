<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200803154840 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'ajout des champs categorie_parent cle etreangere de id de categorie, description et img';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_service ADD categorie_parent INT DEFAULT NULL, ADD description VARCHAR(255) NOT NULL, ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categorie_service ADD CONSTRAINT FK_BE1E3470BBA8CA76 FOREIGN KEY (categorie_parent) REFERENCES categorie_service (id)');
        $this->addSql('CREATE INDEX fk_categorie_service ON categorie_service (categorie_parent)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_service DROP FOREIGN KEY FK_BE1E3470BBA8CA76');
        $this->addSql('DROP INDEX fk_categorie_service ON categorie_service');
        $this->addSql('ALTER TABLE categorie_service DROP categorie_parent, DROP description, DROP img');
    }
}

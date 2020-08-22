<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804072844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'champ description de type text et categorieParent peut être null';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_service DROP FOREIGN KEY FK_BE1E3470BBA8CA76');
        $this->addSql('ALTER TABLE categorie_service CHANGE categorie_parent categorie_parent INT DEFAULT NULL, CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE categorie_service ADD CONSTRAINT FK_BE1E3470BBA8CA76 FOREIGN KEY (categorie_parent) REFERENCES categorie_service (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_service DROP FOREIGN KEY FK_BE1E3470BBA8CA76');
        $this->addSql('ALTER TABLE categorie_service CHANGE categorie_parent categorie_parent INT DEFAULT NULL, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categorie_service ADD CONSTRAINT FK_BE1E3470BBA8CA76 FOREIGN KEY (categorie_parent) REFERENCES categorie_service (id)');
        }
}

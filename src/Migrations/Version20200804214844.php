<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804214844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'ajout du champ img dans Service et suppression du champ prestataire';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_service CHANGE categorie_parent categorie_parent INT DEFAULT NULL');

        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BE3DB2B7');
        $this->addSql('DROP INDEX IDX_E19D9AD2BE3DB2B7 ON service');
        $this->addSql('ALTER TABLE service ADD img VARCHAR(255) NOT NULL, DROP prestataire_id, CHANGE categorie_service_id categorie_service_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_service CHANGE categorie_parent categorie_parent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD prestataire_id INT DEFAULT NULL, DROP img, CHANGE categorie_service_id categorie_service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2BE3DB2B7 ON service (prestataire_id)');
    }
}

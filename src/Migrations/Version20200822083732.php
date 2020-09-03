<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822083732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE sous_categorie_prod');
        $this->addSql('ALTER TABLE user ADD logo LONGBLOB DEFAULT NULL, ADD boutique VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD domaine LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, info_liv_id INT NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D405FC78A (info_liv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sous_categorie_prod (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_sc VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantite INT DEFAULT NULL, INDEX IDX_BFA76F74BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D405FC78A FOREIGN KEY (info_liv_id) REFERENCES info_livraison (id)');
        $this->addSql('ALTER TABLE sous_categorie_prod ADD CONSTRAINT FK_BFA76F74BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_prod (id)');
        $this->addSql('ALTER TABLE user DROP logo, DROP boutique, DROP description, DROP domaine');
    }
}

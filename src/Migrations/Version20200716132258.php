<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200716132258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC279F6E4420');
        $this->addSql('DROP TABLE sous_categorie_prod');
        $this->addSql('ALTER TABLE categorie_prod ADD categorie_parent_id INT DEFAULT NULL, ADD quantite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie_prod ADD CONSTRAINT FK_C29D96C0DF25C577 FOREIGN KEY (categorie_parent_id) REFERENCES categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_C29D96C0DF25C577 ON categorie_prod (categorie_parent_id)');
        $this->addSql('DROP INDEX IDX_29A5EC279F6E4420 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE sous_categorie_prod_id categorie_prod_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275E4B91D7 FOREIGN KEY (categorie_prod_id) REFERENCES categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC275E4B91D7 ON produit (categorie_prod_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sous_categorie_prod (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_sc VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantite INT DEFAULT NULL, INDEX IDX_BFA76F74BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sous_categorie_prod ADD CONSTRAINT FK_BFA76F74BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_prod (id)');
        $this->addSql('ALTER TABLE categorie_prod DROP FOREIGN KEY FK_C29D96C0DF25C577');
        $this->addSql('DROP INDEX IDX_C29D96C0DF25C577 ON categorie_prod');
        $this->addSql('ALTER TABLE categorie_prod DROP categorie_parent_id, DROP quantite');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275E4B91D7');
        $this->addSql('DROP INDEX IDX_29A5EC275E4B91D7 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE categorie_prod_id sous_categorie_prod_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC279F6E4420 FOREIGN KEY (sous_categorie_prod_id) REFERENCES sous_categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC279F6E4420 ON produit (sous_categorie_prod_id)');
    }
}

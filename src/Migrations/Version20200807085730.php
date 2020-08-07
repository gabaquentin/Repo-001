<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807085730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, caracteristique_id INT DEFAULT NULL, dimension_id INT DEFAULT NULL, date_id INT DEFAULT NULL, client_id INT DEFAULT NULL, ville_id INT DEFAULT NULL, categorie_prod_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type_transaction VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, prix_promo DOUBLE PRECISION DEFAULT NULL, images VARCHAR(255) NOT NULL, localisation VARCHAR(255) DEFAULT NULL, visiblite TINYINT(1) NOT NULL, priorite DOUBLE PRECISION NOT NULL, duree_sejour INT DEFAULT NULL, meuble TINYINT(1) DEFAULT NULL, produits_associes VARCHAR(255) DEFAULT NULL, attributs LONGTEXT DEFAULT NULL, nbre_consultations INT NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_29A5EC271704EEB7 (caracteristique_id), UNIQUE INDEX UNIQ_29A5EC27277428AD (dimension_id), UNIQUE INDEX UNIQ_29A5EC27B897366B (date_id), INDEX IDX_29A5EC2719EB6921 (client_id), INDEX IDX_29A5EC27A73F0036 (ville_id), INDEX IDX_29A5EC275E4B91D7 (categorie_prod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC271704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES caracteristiques (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27277428AD FOREIGN KEY (dimension_id) REFERENCES dimension (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2719EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275E4B91D7 FOREIGN KEY (categorie_prod_id) REFERENCES categorie_prod (id)');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE sous_categorie_prod');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F347EFB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, info_liv_id INT NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D405FC78A (info_liv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sous_categorie_prod (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_sc VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantite INT DEFAULT NULL, INDEX IDX_BFA76F74BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D405FC78A FOREIGN KEY (info_liv_id) REFERENCES info_livraison (id)');
        $this->addSql('ALTER TABLE sous_categorie_prod ADD CONSTRAINT FK_BFA76F74BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_prod (id)');
        $this->addSql('DROP TABLE produit');
        $this->addSql('ALTER TABLE user DROP is_verified, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}

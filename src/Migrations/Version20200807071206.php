<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807071206 extends AbstractMigration
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
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, departement VARCHAR(255) DEFAULT NULL, partenariat VARCHAR(255) DEFAULT NULL, last_login VARCHAR(255) DEFAULT NULL, local VARCHAR(255) NOT NULL, creation VARCHAR(255) NOT NULL, image LONGBLOB NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE sous_categorie_prod');
        $this->addSql('ALTER TABLE avis CHANGE produit_id produit_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE boutique CHANGE propritaire_id propritaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caracteristiques CHANGE nbre_chambres nbre_chambres INT DEFAULT NULL, CHANGE nbre_salle_bain nbre_salle_bain INT DEFAULT NULL, CHANGE nbre_parking nbre_parking INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie_prod ADD categorie_parent_id INT DEFAULT NULL, ADD quantite INT DEFAULT NULL, ADD ordre INT NOT NULL, CHANGE type_categorie type_categorie VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie_prod ADD CONSTRAINT FK_C29D96C0DF25C577 FOREIGN KEY (categorie_parent_id) REFERENCES categorie_prod (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C29D96C0DF25C577 ON categorie_prod (categorie_parent_id)');
        $this->addSql('ALTER TABLE categorie_service ADD categorie_parent INT DEFAULT NULL, ADD description VARCHAR(255) NOT NULL, ADD img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categorie_service ADD CONSTRAINT FK_BE1E3470BBA8CA76 FOREIGN KEY (categorie_parent) REFERENCES categorie_service (id)');
        $this->addSql('CREATE INDEX fk_categorie_service ON categorie_service (categorie_parent)');
        $this->addSql('ALTER TABLE chat CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD info_livraison_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE livreur_id livreur_id INT DEFAULT NULL, CHANGE date_livraison date_livraison DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CA3892C82 FOREIGN KEY (info_livraison_id) REFERENCES info_livraison (id)');
        $this->addSql('CREATE INDEX IDX_35D4282CA3892C82 ON commandes (info_livraison_id)');
        $this->addSql('ALTER TABLE date CHANGE date_modification date_modification DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE demande CHANGE service_id service_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dimension ADD longueur DOUBLE PRECISION DEFAULT NULL, ADD hauteur DOUBLE PRECISION DEFAULT NULL, DROP longeur, CHANGE largeur largeur DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE emplacement CHANGE boutique_id boutique_id INT DEFAULT NULL, CHANGE terrain_id terrain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE info_livraison CHANGE pays pays VARCHAR(255) DEFAULT NULL, CHANGE quartier quartier VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE info_user CHANGE pays pays VARCHAR(255) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27B897366B, ADD UNIQUE INDEX UNIQ_29A5EC27B897366B (date_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC271704EEB7, ADD UNIQUE INDEX UNIQ_29A5EC271704EEB7 (caracteristique_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27277428AD, ADD UNIQUE INDEX UNIQ_29A5EC27277428AD (dimension_id)');
        $this->addSql('DROP INDEX IDX_29A5EC279F6E4420 ON produit');
        $this->addSql('ALTER TABLE produit ADD categorie_prod_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, DROP sous_categorie_prod_id, CHANGE caracteristique_id caracteristique_id INT DEFAULT NULL, CHANGE dimension_id dimension_id INT DEFAULT NULL, CHANGE date_id date_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE prix_promo prix_promo DOUBLE PRECISION DEFAULT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL, CHANGE duree_sejour duree_sejour INT DEFAULT NULL, CHANGE meuble meuble TINYINT(1) DEFAULT NULL, CHANGE produits_associes produits_associes VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275E4B91D7 FOREIGN KEY (categorie_prod_id) REFERENCES categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC275E4B91D7 ON produit (categorie_prod_id)');
        $this->addSql('ALTER TABLE reclamation CHANGE client_id client_id INT DEFAULT NULL, CHANGE administrateur_id administrateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE categorie_service_id categorie_service_id INT DEFAULT NULL, CHANGE prestataire_id prestataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain CHANGE proprietaire_id proprietaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE extra_info_id extra_info_id INT DEFAULT NULL, CHANGE info_user_id info_user_id INT DEFAULT NULL, CHANGE telephone telephone INT DEFAULT NULL, CHANGE departement departement VARCHAR(255) DEFAULT NULL, CHANGE type_partenaire type_partenaire VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, info_liv_id INT NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D405FC78A (info_liv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sous_categorie_prod (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_sc VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantite INT DEFAULT NULL, INDEX IDX_BFA76F74BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D405FC78A FOREIGN KEY (info_liv_id) REFERENCES info_livraison (id)');
        $this->addSql('ALTER TABLE sous_categorie_prod ADD CONSTRAINT FK_BFA76F74BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_prod (id)');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE avis CHANGE produit_id produit_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE boutique CHANGE propritaire_id propritaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caracteristiques CHANGE nbre_chambres nbre_chambres INT DEFAULT NULL, CHANGE nbre_salle_bain nbre_salle_bain INT DEFAULT NULL, CHANGE nbre_parking nbre_parking INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie_prod DROP FOREIGN KEY FK_C29D96C0DF25C577');
        $this->addSql('DROP INDEX IDX_C29D96C0DF25C577 ON categorie_prod');
        $this->addSql('ALTER TABLE categorie_prod DROP categorie_parent_id, DROP quantite, DROP ordre, CHANGE type_categorie type_categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categorie_service DROP FOREIGN KEY FK_BE1E3470BBA8CA76');
        $this->addSql('DROP INDEX fk_categorie_service ON categorie_service');
        $this->addSql('ALTER TABLE categorie_service DROP categorie_parent, DROP description, DROP img');
        $this->addSql('ALTER TABLE chat CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CA3892C82');
        $this->addSql('DROP INDEX IDX_35D4282CA3892C82 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP info_livraison_id, CHANGE client_id client_id INT DEFAULT NULL, CHANGE livreur_id livreur_id INT DEFAULT NULL, CHANGE date_livraison date_livraison DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE date CHANGE date_modification date_modification DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE demande CHANGE service_id service_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dimension ADD longeur DOUBLE PRECISION NOT NULL, DROP longueur, DROP hauteur, CHANGE largeur largeur DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE emplacement CHANGE boutique_id boutique_id INT DEFAULT NULL, CHANGE terrain_id terrain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE info_livraison CHANGE pays pays VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE quartier quartier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE info_user CHANGE pays pays VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC271704EEB7, ADD INDEX IDX_29A5EC271704EEB7 (caracteristique_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27277428AD, ADD INDEX IDX_29A5EC27277428AD (dimension_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27B897366B, ADD INDEX IDX_29A5EC27B897366B (date_id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275E4B91D7');
        $this->addSql('DROP INDEX IDX_29A5EC275E4B91D7 ON produit');
        $this->addSql('ALTER TABLE produit ADD sous_categorie_prod_id INT DEFAULT NULL, DROP categorie_prod_id, DROP description, CHANGE caracteristique_id caracteristique_id INT DEFAULT NULL, CHANGE dimension_id dimension_id INT DEFAULT NULL, CHANGE date_id date_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL, CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE prix_promo prix_promo DOUBLE PRECISION DEFAULT \'NULL\', CHANGE localisation localisation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE duree_sejour duree_sejour INT DEFAULT NULL, CHANGE meuble meuble TINYINT(1) DEFAULT \'NULL\', CHANGE produits_associes produits_associes VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC279F6E4420 FOREIGN KEY (sous_categorie_prod_id) REFERENCES sous_categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC279F6E4420 ON produit (sous_categorie_prod_id)');
        $this->addSql('ALTER TABLE reclamation CHANGE client_id client_id INT DEFAULT NULL, CHANGE administrateur_id administrateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE categorie_service_id categorie_service_id INT DEFAULT NULL, CHANGE prestataire_id prestataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain CHANGE proprietaire_id proprietaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE info_user_id info_user_id INT DEFAULT NULL, CHANGE extra_info_id extra_info_id INT DEFAULT NULL, CHANGE telephone telephone INT DEFAULT NULL, CHANGE departement departement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE type_partenaire type_partenaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200715153029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attribut (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, valeurs LONGTEXT NOT NULL, visibilite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, client_id INT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, commentaire LONGTEXT DEFAULT NULL, likes INT NOT NULL, dislike INT NOT NULL, INDEX IDX_8F91ABF0F347EFB (produit_id), INDEX IDX_8F91ABF019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE boutique (id INT AUTO_INCREMENT NOT NULL, propritaire_id INT DEFAULT NULL, nom_boutique VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, INDEX IDX_A1223C547B2EEE88 (propritaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristiques (id INT AUTO_INCREMENT NOT NULL, nbre_chambres INT DEFAULT NULL, nbre_salle_bain INT DEFAULT NULL, nbre_parking INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_prod (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, type_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, INDEX IDX_659DF2AAFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, info_liv_id INT NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D405FC78A (info_liv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, livreur_id INT DEFAULT NULL, date_com DATETIME NOT NULL, date_livraison DATETIME DEFAULT NULL, mode_paiement VARCHAR(255) NOT NULL, panier LONGTEXT NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_35D4282C19EB6921 (client_id), INDEX IDX_35D4282CF8646701 (livreur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, config LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date (id INT AUTO_INCREMENT NOT NULL, date_ajout DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, client_id INT DEFAULT NULL, localisation VARCHAR(255) NOT NULL, date DATETIME NOT NULL, photos LONGTEXT NOT NULL, INDEX IDX_2694D7A5ED5CA9E6 (service_id), INDEX IDX_2694D7A519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dimension (id INT AUTO_INCREMENT NOT NULL, longeur DOUBLE PRECISION NOT NULL, largeur DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, chat_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C0B9F90F1A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, terrain_id INT DEFAULT NULL, latitude VARCHAR(255) NOT NULL, logitude VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C0CF65F6AB677BE6 (boutique_id), UNIQUE INDEX UNIQ_C0CF65F68A2D8B41 (terrain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_info (id INT AUTO_INCREMENT NOT NULL, whishlist LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, note VARCHAR(255) NOT NULL, commentaire LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_livraison (id INT AUTO_INCREMENT NOT NULL, pays VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, quartier VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_user (id INT AUTO_INCREMENT NOT NULL, pays VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, quartier VARCHAR(255) NOT NULL, code_postal VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, discussion_id INT NOT NULL, envoyeur_id INT NOT NULL, receveur_id INT NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_B6BD307F1ADED311 (discussion_id), INDEX IDX_B6BD307F4795A786 (envoyeur_id), INDEX IDX_B6BD307FB967E626 (receveur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, caracteristique_id INT DEFAULT NULL, dimension_id INT DEFAULT NULL, date_id INT DEFAULT NULL, client_id INT DEFAULT NULL, ville_id INT DEFAULT NULL, sous_categorie_prod_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type_transaction VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, prix_promo DOUBLE PRECISION DEFAULT NULL, images VARCHAR(255) NOT NULL, localisation VARCHAR(255) DEFAULT NULL, visiblite TINYINT(1) NOT NULL, priorite DOUBLE PRECISION NOT NULL, duree_sejour INT DEFAULT NULL, meuble TINYINT(1) DEFAULT NULL, produits_associes VARCHAR(255) DEFAULT NULL, attributs LONGTEXT DEFAULT NULL, nbre_consultations INT NOT NULL, INDEX IDX_29A5EC271704EEB7 (caracteristique_id), INDEX IDX_29A5EC27277428AD (dimension_id), INDEX IDX_29A5EC27B897366B (date_id), INDEX IDX_29A5EC2719EB6921 (client_id), INDEX IDX_29A5EC27A73F0036 (ville_id), INDEX IDX_29A5EC279F6E4420 (sous_categorie_prod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, administrateur_id INT DEFAULT NULL, object VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_CE60640419EB6921 (client_id), INDEX IDX_CE6064047EE5403C (administrateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, categorie_service_id INT DEFAULT NULL, prestataire_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_E19D9AD27395634A (categorie_service_id), INDEX IDX_E19D9AD2BE3DB2B7 (prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categorie_prod (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_sc VARCHAR(255) NOT NULL, quantite INT DEFAULT NULL, INDEX IDX_BFA76F74BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrain (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT DEFAULT NULL, longeur DOUBLE PRECISION NOT NULL, largeur DOUBLE PRECISION NOT NULL, photos VARCHAR(255) NOT NULL, INDEX IDX_C87653B176C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, extra_info_id INT DEFAULT NULL, info_user_id INT DEFAULT NULL, telephone INT DEFAULT NULL, departement VARCHAR(255) DEFAULT NULL, type_partenaire VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B38550C61A (extra_info_id), UNIQUE INDEX UNIQ_1D1C63B325ABFA0B (info_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, villes VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF019EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C547B2EEE88 FOREIGN KEY (propritaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D405FC78A FOREIGN KEY (info_liv_id) REFERENCES info_livraison (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C19EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF8646701 FOREIGN KEY (livreur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A519EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F6AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F68A2D8B41 FOREIGN KEY (terrain_id) REFERENCES terrain (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4795A786 FOREIGN KEY (envoyeur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB967E626 FOREIGN KEY (receveur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC271704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES caracteristiques (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27277428AD FOREIGN KEY (dimension_id) REFERENCES dimension (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2719EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC279F6E4420 FOREIGN KEY (sous_categorie_prod_id) REFERENCES sous_categorie_prod (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640419EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047EE5403C FOREIGN KEY (administrateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD27395634A FOREIGN KEY (categorie_service_id) REFERENCES categorie_service (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE sous_categorie_prod ADD CONSTRAINT FK_BFA76F74BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_prod (id)');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B176C50E4A FOREIGN KEY (proprietaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B38550C61A FOREIGN KEY (extra_info_id) REFERENCES extra_info (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B325ABFA0B FOREIGN KEY (info_user_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F6AB677BE6');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC271704EEB7');
        $this->addSql('ALTER TABLE sous_categorie_prod DROP FOREIGN KEY FK_BFA76F74BCF5E72D');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD27395634A');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F1A9A7125');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B897366B');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27277428AD');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1ADED311');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B38550C61A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D405FC78A');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F347EFB');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5ED5CA9E6');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC279F6E4420');
        $this->addSql('ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F68A2D8B41');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF019EB6921');
        $this->addSql('ALTER TABLE boutique DROP FOREIGN KEY FK_A1223C547B2EEE88');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAFB88E14F');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C19EB6921');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF8646701');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A519EB6921');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F4795A786');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB967E626');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2719EB6921');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640419EB6921');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047EE5403C');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BE3DB2B7');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B176C50E4A');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B325ABFA0B');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A73F0036');
        $this->addSql('DROP TABLE attribut');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE boutique');
        $this->addSql('DROP TABLE caracteristiques');
        $this->addSql('DROP TABLE categorie_prod');
        $this->addSql('DROP TABLE categorie_service');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE date');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE dimension');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE emplacement');
        $this->addSql('DROP TABLE extra_info');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE info_livraison');
        $this->addSql('DROP TABLE info_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE sous_categorie_prod');
        $this->addSql('DROP TABLE terrain');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE ville');
    }
}

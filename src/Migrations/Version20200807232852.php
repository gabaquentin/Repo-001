<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807232852 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'suppression des champs like et dislike de l entite Avis';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

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
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE avis DROP likes, DROP dislike');
        $this->addSql('ALTER TABLE boutique DROP FOREIGN KEY FK_A1223C547B2EEE88');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C547B2EEE88 FOREIGN KEY (propritaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAFB88E14F');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C19EB6921');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF8646701');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF8646701 FOREIGN KEY (livreur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A519EB6921');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A519EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F4795A786');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB967E626');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4795A786 FOREIGN KEY (envoyeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB967E626 FOREIGN KEY (receveur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2719EB6921');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2719EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640419EB6921');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047EE5403C');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640419EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047EE5403C FOREIGN KEY (administrateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BE3DB2B7');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B176C50E4A');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B176C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD info_user_id INT DEFAULT NULL, ADD extra_info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64925ABFA0B FOREIGN KEY (info_user_id) REFERENCES info_user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498550C61A FOREIGN KEY (extra_info_id) REFERENCES extra_info (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64925ABFA0B ON user (info_user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498550C61A ON user (extra_info_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, info_user_id INT DEFAULT NULL, extra_info_id INT DEFAULT NULL, telephone INT DEFAULT NULL, departement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type_partenaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_1D1C63B325ABFA0B (info_user_id), UNIQUE INDEX UNIQ_1D1C63B38550C61A (extra_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B325ABFA0B FOREIGN KEY (info_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B38550C61A FOREIGN KEY (extra_info_id) REFERENCES extra_info (id)');
        $this->addSql('ALTER TABLE avis ADD likes INT NOT NULL, ADD dislike INT NOT NULL');
        $this->addSql('ALTER TABLE boutique DROP FOREIGN KEY FK_A1223C547B2EEE88');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C547B2EEE88 FOREIGN KEY (propritaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAFB88E14F');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C19EB6921');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF8646701');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C19EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF8646701 FOREIGN KEY (livreur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A519EB6921');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A519EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F4795A786');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB967E626');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4795A786 FOREIGN KEY (envoyeur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB967E626 FOREIGN KEY (receveur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2719EB6921');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2719EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640419EB6921');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047EE5403C');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640419EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047EE5403C FOREIGN KEY (administrateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BE3DB2B7');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B176C50E4A');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B176C50E4A FOREIGN KEY (proprietaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64925ABFA0B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498550C61A');
        $this->addSql('DROP INDEX UNIQ_8D93D64925ABFA0B ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6498550C61A ON user');
        $this->addSql('ALTER TABLE user DROP info_user_id, DROP extra_info_id');
    }
}

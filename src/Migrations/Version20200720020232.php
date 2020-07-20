<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200720020232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_prod DROP FOREIGN KEY FK_C29D96C0DF25C577');
        $this->addSql('DROP INDEX IDX_C29D96C0DF25C577 ON categorie_prod');
        $this->addSql('ALTER TABLE categorie_prod ADD categorie_parent VARCHAR(255) DEFAULT NULL, DROP categorie_parent_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B38550C61A');
        $this->addSql('DROP INDEX UNIQ_1D1C63B38550C61A ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP extra_info_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_prod ADD categorie_parent_id INT DEFAULT NULL, DROP categorie_parent');
        $this->addSql('ALTER TABLE categorie_prod ADD CONSTRAINT FK_C29D96C0DF25C577 FOREIGN KEY (categorie_parent_id) REFERENCES categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_C29D96C0DF25C577 ON categorie_prod (categorie_parent_id)');
        $this->addSql('ALTER TABLE utilisateur ADD extra_info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B38550C61A FOREIGN KEY (extra_info_id) REFERENCES extra_info (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B38550C61A ON utilisateur (extra_info_id)');
    }
}

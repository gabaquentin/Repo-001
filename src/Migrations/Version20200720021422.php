<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200720021422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_prod ADD categorie_parent_id INT DEFAULT NULL, DROP categorie_parent');
        $this->addSql('ALTER TABLE categorie_prod ADD CONSTRAINT FK_C29D96C0DF25C577 FOREIGN KEY (categorie_parent_id) REFERENCES categorie_prod (id)');
        $this->addSql('CREATE INDEX IDX_C29D96C0DF25C577 ON categorie_prod (categorie_parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie_prod DROP FOREIGN KEY FK_C29D96C0DF25C577');
        $this->addSql('DROP INDEX IDX_C29D96C0DF25C577 ON categorie_prod');
        $this->addSql('ALTER TABLE categorie_prod ADD categorie_parent VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP categorie_parent_id');
    }
}

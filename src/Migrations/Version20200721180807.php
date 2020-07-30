<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721180807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27277428AD, ADD UNIQUE INDEX UNIQ_29A5EC27277428AD (dimension_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27B897366B, ADD UNIQUE INDEX UNIQ_29A5EC27B897366B (date_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27A73F0036, ADD UNIQUE INDEX UNIQ_29A5EC27A73F0036 (ville_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC271704EEB7, ADD UNIQUE INDEX UNIQ_29A5EC271704EEB7 (caracteristique_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC271704EEB7, ADD INDEX IDX_29A5EC271704EEB7 (caracteristique_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27277428AD, ADD INDEX IDX_29A5EC27277428AD (dimension_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27B897366B, ADD INDEX IDX_29A5EC27B897366B (date_id)');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27A73F0036, ADD INDEX IDX_29A5EC27A73F0036 (ville_id)');
    }
}

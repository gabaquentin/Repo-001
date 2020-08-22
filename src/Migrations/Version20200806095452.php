<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806095452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'creation de l entite QuestionService';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE question_service (id INT AUTO_INCREMENT NOT NULL, service INT NOT NULL, question LONGTEXT NOT NULL, reponses LONGTEXT NOT NULL, typeQuestion VARCHAR(3) NOT NULL, autre TINYINT(1) NOT NULL, INDEX IDX_EF414FCFE19D9AD2 (service), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE question_service');

    }
}

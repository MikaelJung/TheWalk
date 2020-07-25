<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717162024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE date_add date_add DATETIME DEFAULT NULL, CHANGE message text LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE subject CHANGE date_add date_add DATETIME DEFAULT NULL, CHANGE block block TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE date_registration date_registration DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE date_add date_add DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE text message LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE subject CHANGE date_add date_add DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE block block TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON DEFAULT NULL, CHANGE date_registration date_registration DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}

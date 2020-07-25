<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717183547 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE date_add date_add DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE subject CHANGE date_add date_add DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE date_add date_add DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE subject CHANGE date_add date_add DATETIME DEFAULT CURRENT_TIMESTAMP');
    }
}

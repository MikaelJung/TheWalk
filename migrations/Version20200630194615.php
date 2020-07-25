<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200630194615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, place VARCHAR(50) NOT NULL, date DATE DEFAULT NULL, nb_place_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_user (activity_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8E570DDB81C06096 (activity_id), INDEX IDX_8E570DDBA76ED395 (user_id), PRIMARY KEY(activity_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, message LONGTEXT NOT NULL, date_add DATETIME DEFAULT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), INDEX IDX_B6BD307F23EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, date_add DATETIME DEFAULT NULL, block TINYINT(1) NOT NULL, INDEX IDX_FBCE3E7AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, task VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, pseudo VARCHAR(50) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, date_registration DATETIME DEFAULT NULL, activation_token VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), INDEX IDX_8D93D6498DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_picture (user_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_4ED65183A76ED395 (user_id), INDEX IDX_4ED65183EE45BDBF (picture_id), PRIMARY KEY(user_id, picture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_video (user_id INT NOT NULL, video_id INT NOT NULL, INDEX IDX_9E052174A76ED395 (user_id), INDEX IDX_9E05217429C1004E (video_id), PRIMARY KEY(user_id, video_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_user ADD CONSTRAINT FK_8E570DDB81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_user ADD CONSTRAINT FK_8E570DDBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE user_picture ADD CONSTRAINT FK_4ED65183A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_picture ADD CONSTRAINT FK_4ED65183EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_video ADD CONSTRAINT FK_9E052174A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_video ADD CONSTRAINT FK_9E05217429C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_user DROP FOREIGN KEY FK_8E570DDB81C06096');
        $this->addSql('ALTER TABLE user_picture DROP FOREIGN KEY FK_4ED65183EE45BDBF');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F23EDC87');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498DB60186');
        $this->addSql('ALTER TABLE activity_user DROP FOREIGN KEY FK_8E570DDBA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7AA76ED395');
        $this->addSql('ALTER TABLE user_picture DROP FOREIGN KEY FK_4ED65183A76ED395');
        $this->addSql('ALTER TABLE user_video DROP FOREIGN KEY FK_9E052174A76ED395');
        $this->addSql('ALTER TABLE user_video DROP FOREIGN KEY FK_9E05217429C1004E');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_picture');
        $this->addSql('DROP TABLE user_video');
        $this->addSql('DROP TABLE video');
    }
}

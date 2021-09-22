<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922074417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_B6F7494EBAF4AE56');
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, sondage_id, titre FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sondage_id INTEGER NOT NULL, titre VARCHAR(120) NOT NULL COLLATE BINARY, CONSTRAINT FK_B6F7494EBAF4AE56 FOREIGN KEY (sondage_id) REFERENCES sondage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO question (id, sondage_id, titre) SELECT id, sondage_id, titre FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494EBAF4AE56 ON question (sondage_id)');
        $this->addSql('DROP INDEX IDX_5FB6DEC71E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reponse AS SELECT id, question_id, titre, score FROM reponse');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('CREATE TABLE reponse (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, titre VARCHAR(120) NOT NULL COLLATE BINARY, score INTEGER NOT NULL, CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reponse (id, question_id, titre, score) SELECT id, question_id, titre, score FROM __temp__reponse');
        $this->addSql('DROP TABLE __temp__reponse');
        $this->addSql('CREATE INDEX IDX_5FB6DEC71E27F6BF ON reponse (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_B6F7494EBAF4AE56');
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, sondage_id, titre FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sondage_id INTEGER NOT NULL, titre VARCHAR(120) NOT NULL)');
        $this->addSql('INSERT INTO question (id, sondage_id, titre) SELECT id, sondage_id, titre FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494EBAF4AE56 ON question (sondage_id)');
        $this->addSql('DROP INDEX IDX_5FB6DEC71E27F6BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reponse AS SELECT id, question_id, titre, score FROM reponse');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('CREATE TABLE reponse (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question_id INTEGER NOT NULL, titre VARCHAR(120) NOT NULL, score INTEGER NOT NULL)');
        $this->addSql('INSERT INTO reponse (id, question_id, titre, score) SELECT id, question_id, titre, score FROM __temp__reponse');
        $this->addSql('DROP TABLE __temp__reponse');
        $this->addSql('CREATE INDEX IDX_5FB6DEC71E27F6BF ON reponse (question_id)');
    }
}

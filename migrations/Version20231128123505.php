<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128123505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lesson_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lesson (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, material VARCHAR(255) NOT NULL, duration VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lesson_course (lesson_id INT NOT NULL, course_id INT NOT NULL, PRIMARY KEY(lesson_id, course_id))');
        $this->addSql('CREATE INDEX IDX_65089FEECDF80196 ON lesson_course (lesson_id)');
        $this->addSql('CREATE INDEX IDX_65089FEE591CC992 ON lesson_course (course_id)');
        $this->addSql('ALTER TABLE lesson_course ADD CONSTRAINT FK_65089FEECDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson_course ADD CONSTRAINT FK_65089FEE591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE lesson_id_seq CASCADE');
        $this->addSql('ALTER TABLE lesson_course DROP CONSTRAINT FK_65089FEECDF80196');
        $this->addSql('ALTER TABLE lesson_course DROP CONSTRAINT FK_65089FEE591CC992');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE lesson_course');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129122530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE feedback_user (feedback_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(feedback_id, user_id))');
        $this->addSql('CREATE INDEX IDX_483137C0D249A887 ON feedback_user (feedback_id)');
        $this->addSql('CREATE INDEX IDX_483137C0A76ED395 ON feedback_user (user_id)');
        $this->addSql('CREATE TABLE feedback_lesson (feedback_id INT NOT NULL, lesson_id INT NOT NULL, PRIMARY KEY(feedback_id, lesson_id))');
        $this->addSql('CREATE INDEX IDX_C53840BDD249A887 ON feedback_lesson (feedback_id)');
        $this->addSql('CREATE INDEX IDX_C53840BDCDF80196 ON feedback_lesson (lesson_id)');
        $this->addSql('ALTER TABLE feedback_user ADD CONSTRAINT FK_483137C0D249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback_user ADD CONSTRAINT FK_483137C0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback_lesson ADD CONSTRAINT FK_C53840BDD249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback_lesson ADD CONSTRAINT FK_C53840BDCDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('ALTER TABLE feedback_user DROP CONSTRAINT FK_483137C0D249A887');
        $this->addSql('ALTER TABLE feedback_user DROP CONSTRAINT FK_483137C0A76ED395');
        $this->addSql('ALTER TABLE feedback_lesson DROP CONSTRAINT FK_C53840BDD249A887');
        $this->addSql('ALTER TABLE feedback_lesson DROP CONSTRAINT FK_C53840BDCDF80196');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE feedback_user');
        $this->addSql('DROP TABLE feedback_lesson');
    }
}

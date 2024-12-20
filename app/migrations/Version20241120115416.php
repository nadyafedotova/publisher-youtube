<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120115416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE book_content (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, content VARCHAR(255) NOT NULL, is_published BOOLEAN DEFAULT false NOT NULL, chapter_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DE5183F579F4768 ON book_content (chapter_id)');
        $this->addSql('ALTER TABLE book_content ADD CONSTRAINT FK_6DE5183F579F4768 FOREIGN KEY (chapter_id) REFERENCES book_chapter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book_content DROP CONSTRAINT FK_6DE5183F579F4768');
        $this->addSql('DROP TABLE book_content');
    }
}

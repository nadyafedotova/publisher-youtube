<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613092520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book_book_to_book_format DROP CONSTRAINT fk_d3d5d66716a2b381');
        $this->addSql('ALTER TABLE book_book_to_book_format DROP CONSTRAINT fk_d3d5d667741f9a75');
        $this->addSql('DROP TABLE book_book_to_book_format');
        $this->addSql('ALTER TABLE book_format ADD title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE book_book_to_book_format (book_id INT NOT NULL, book_to_book_format_id INT NOT NULL, PRIMARY KEY(book_id, book_to_book_format_id))');
        $this->addSql('CREATE INDEX idx_d3d5d667741f9a75 ON book_book_to_book_format (book_to_book_format_id)');
        $this->addSql('CREATE INDEX idx_d3d5d66716a2b381 ON book_book_to_book_format (book_id)');
        $this->addSql('ALTER TABLE book_book_to_book_format ADD CONSTRAINT fk_d3d5d66716a2b381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_book_to_book_format ADD CONSTRAINT fk_d3d5d667741f9a75 FOREIGN KEY (book_to_book_format_id) REFERENCES book_to_book_format (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_format DROP title');
    }
}

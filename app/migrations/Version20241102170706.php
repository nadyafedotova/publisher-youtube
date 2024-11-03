<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102170706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO book_format (id, title, description, comment) VALUES(1, 'eBook', 'Make accurate time series predictions with powerful pretrained foundation models!', null )");
        $this->addSql("INSERT INTO book_format (id, title, description, comment) VALUES(2, 'print', 'shipping optionsour return/exchange policy', 'In Time Series Forecasting Using Foundation Models you will discover' )");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE book_format');
    }
}

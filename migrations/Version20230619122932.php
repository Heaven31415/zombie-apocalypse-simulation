<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230619122932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add x and y to resources';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE resource ADD x INT NOT NULL');
        $this->addSql('ALTER TABLE resource ADD y INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE resource DROP x');
        $this->addSql('ALTER TABLE resource DROP y');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230619101757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add zombies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE zombie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE zombie (id INT NOT NULL, name VARCHAR(255) NOT NULL, x INT NOT NULL, y INT NOT NULL, PRIMARY KEY(id))'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE zombie_id_seq CASCADE');
        $this->addSql('DROP TABLE zombie');
    }
}

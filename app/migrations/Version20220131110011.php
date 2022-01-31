<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131110011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d649fbdb2615');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN y TO first_name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A9D1C132 ON "user" (first_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_8D93D649A9D1C132');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN first_name TO y');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649fbdb2615 ON "user" (y)');
    }
}

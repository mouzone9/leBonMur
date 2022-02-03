<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202161659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement ADD seller_id INT NOT NULL');
        $this->addSql('ALTER TABLE advertisement ADD CONSTRAINT FK_C95F6AEE8DE820D9 FOREIGN KEY (seller_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C95F6AEE8DE820D9 ON advertisement (seller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE advertisement DROP CONSTRAINT FK_C95F6AEE8DE820D9');
        $this->addSql('DROP INDEX IDX_C95F6AEE8DE820D9');
        $this->addSql('ALTER TABLE advertisement DROP seller_id');
    }
}

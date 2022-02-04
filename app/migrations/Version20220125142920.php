<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125142920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE advertisement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE advertisement (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, pictures TEXT NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN advertisement.pictures IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(100) NOT NULL, pictogram VARCHAR(255) NOT NULL, color VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag_advertisement (tag_id INT NOT NULL, advertisement_id INT NOT NULL, PRIMARY KEY(tag_id, advertisement_id))');
        $this->addSql('CREATE INDEX IDX_27F11DE9BAD26311 ON tag_advertisement (tag_id)');
        $this->addSql('CREATE INDEX IDX_27F11DE9A1FBF71B ON tag_advertisement (advertisement_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE tag_advertisement ADD CONSTRAINT FK_27F11DE9BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_advertisement ADD CONSTRAINT FK_27F11DE9A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tag_advertisement DROP CONSTRAINT FK_27F11DE9A1FBF71B');
        $this->addSql('ALTER TABLE tag_advertisement DROP CONSTRAINT FK_27F11DE9BAD26311');
        $this->addSql('DROP SEQUENCE advertisement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_advertisement');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

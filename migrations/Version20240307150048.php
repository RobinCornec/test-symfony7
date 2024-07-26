<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307150048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, panda_score_id INT NOT NULL, name VARCHAR(127) NOT NULL, slug VARCHAR(127) NOT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C2DD8A724 ON game (panda_score_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C989D9B62 ON game (slug)');
        $this->addSql('COMMENT ON COLUMN game.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE league (id UUID NOT NULL, game_id UUID DEFAULT NULL, panda_score_id INT NOT NULL, name VARCHAR(127) NOT NULL, slug VARCHAR(31) NOT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EB4C3182DD8A724 ON league (panda_score_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EB4C318989D9B62 ON league (slug)');
        $this->addSql('CREATE INDEX IDX_3EB4C318E48FD905 ON league (game_id)');
        $this->addSql('COMMENT ON COLUMN league.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN league.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE serie (id UUID NOT NULL, league_id UUID DEFAULT NULL, game_id UUID DEFAULT NULL, panda_score_id INT NOT NULL, name VARCHAR(127) NOT NULL, slug VARCHAR(127) NOT NULL, active BOOLEAN NOT NULL, winner_id INT DEFAULT NULL, winner_type VARCHAR(255) NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA3A93342DD8A724 ON serie (panda_score_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA3A9334989D9B62 ON serie (slug)');
        $this->addSql('CREATE INDEX IDX_AA3A933458AFC4DE ON serie (league_id)');
        $this->addSql('CREATE INDEX IDX_AA3A9334E48FD905 ON serie (game_id)');
        $this->addSql('COMMENT ON COLUMN serie.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN serie.league_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN serie.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN serie.begin_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN serie.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A933458AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A9334E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE league DROP CONSTRAINT FK_3EB4C318E48FD905');
        $this->addSql('ALTER TABLE serie DROP CONSTRAINT FK_AA3A933458AFC4DE');
        $this->addSql('ALTER TABLE serie DROP CONSTRAINT FK_AA3A9334E48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

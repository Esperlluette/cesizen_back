<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611164540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE app_user (id SERIAL NOT NULL, profile_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, country_from VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, acct_created_date DATE NOT NULL, acct_last_update_date DATE DEFAULT NULL, is_active BOOLEAN NOT NULL, is_suppressed BOOLEAN NOT NULL, recover_code VARCHAR(255) NOT NULL, recovery_date DATE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_88BDF3E9CCFA12B8 ON app_user (profile_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON app_user (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE breath_excercies (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, timer_breath_in INT NOT NULL, timer_breath_out INT NOT NULL, timer_apnea INT NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE menu (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, order_type INT NOT NULL, is_active BOOLEAN NOT NULL, creation_date DATE NOT NULL, modification_date DATE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE page (id SERIAL NOT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content JSON NOT NULL, creation_date DATE NOT NULL, last_update_date DATE DEFAULT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_140AB620B03A8386 ON page (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_140AB620896DBBDE ON page (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profile (id SERIAL NOT NULL, code VARCHAR(10) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE page ADD CONSTRAINT FK_140AB620B03A8386 FOREIGN KEY (created_by_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE page ADD CONSTRAINT FK_140AB620896DBBDE FOREIGN KEY (updated_by_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE app_user DROP CONSTRAINT FK_88BDF3E9CCFA12B8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE page DROP CONSTRAINT FK_140AB620B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE page DROP CONSTRAINT FK_140AB620896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE app_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE breath_excercies
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE menu
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE page
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE profile
        SQL);
    }
}

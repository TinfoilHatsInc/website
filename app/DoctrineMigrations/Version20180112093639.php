<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180112093639 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dead_module (id INT AUTO_INCREMENT NOT NULL, chub_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', module_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CB86BE11FA884824 (chub_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dead_module ADD CONSTRAINT FK_CB86BE11FA884824 FOREIGN KEY (chub_id) REFERENCES chub (id)');
        $this->addSql('ALTER TABLE chub CHANGE alarm_status alarm_status ENUM(\'off\', \'armed\', \'on\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE dead_module');
        $this->addSql('ALTER TABLE chub CHANGE alarm_status alarm_status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}

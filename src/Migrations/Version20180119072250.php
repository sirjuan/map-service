<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180119072250 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marker ADD location_id VARCHAR(27) DEFAULT NULL, ADD type VARCHAR(255) NOT NULL, ADD color VARCHAR(255) NOT NULL, DROP marker_type, DROP marker_color');
        $this->addSql('ALTER TABLE marker ADD CONSTRAINT FK_82CF20FE64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_82CF20FE64D218E ON marker (location_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marker DROP FOREIGN KEY FK_82CF20FE64D218E');
        $this->addSql('DROP INDEX IDX_82CF20FE64D218E ON marker');
        $this->addSql('ALTER TABLE marker ADD marker_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD marker_color VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP location_id, DROP type, DROP color');
    }
}

<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118080221 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE marker (id INT AUTO_INCREMENT NOT NULL, marker_type VARCHAR(255) NOT NULL, marker_color VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location DROP message, DROP place_id, DROP marker_type, DROP marker_color, CHANGE id id VARCHAR(27) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE marker');
        $this->addSql('ALTER TABLE location ADD message VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD place_id VARCHAR(27) NOT NULL COLLATE utf8_unicode_ci, ADD marker_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD marker_color VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}

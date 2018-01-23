<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122172837 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image ADD submitter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F919E5513 FOREIGN KEY (submitter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F919E5513 ON image (submitter_id)');
        $this->addSql('ALTER TABLE location ADD submitter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB919E5513 FOREIGN KEY (submitter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB919E5513 ON location (submitter_id)');
        $this->addSql('ALTER TABLE marker ADD submitter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marker ADD CONSTRAINT FK_82CF20FE919E5513 FOREIGN KEY (submitter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_82CF20FE919E5513 ON marker (submitter_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F919E5513');
        $this->addSql('DROP INDEX IDX_C53D045F919E5513 ON image');
        $this->addSql('ALTER TABLE image DROP submitter_id');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB919E5513');
        $this->addSql('DROP INDEX IDX_5E9E89CB919E5513 ON location');
        $this->addSql('ALTER TABLE location DROP submitter_id');
        $this->addSql('ALTER TABLE marker DROP FOREIGN KEY FK_82CF20FE919E5513');
        $this->addSql('DROP INDEX IDX_82CF20FE919E5513 ON marker');
        $this->addSql('ALTER TABLE marker DROP submitter_id');
    }
}

<?php
declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200517121828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE astrologer_service (id INT AUTO_INCREMENT NOT NULL, astrologer_id INT NOT NULL, service_id INT NOT NULL, price NUMERIC(8, 2) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_896418B156F716EE (astrologer_id), INDEX IDX_896418B1ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE astrologer_service ADD CONSTRAINT FK_896418B156F716EE FOREIGN KEY (astrologer_id) REFERENCES astrologer (id)');
        $this->addSql('ALTER TABLE astrologer_service ADD CONSTRAINT FK_896418B1ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E19D9AD25E237E06 ON service (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A503481E7927C74 ON astrologer (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A503481F96E6191 ON astrologer (image_path)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE astrologer_service');
        $this->addSql('DROP INDEX UNIQ_6A503481E7927C74 ON astrologer');
        $this->addSql('DROP INDEX UNIQ_6A503481F96E6191 ON astrologer');
        $this->addSql('DROP INDEX UNIQ_E19D9AD25E237E06 ON service');
    }
}

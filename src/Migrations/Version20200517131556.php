<?php
declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200517131556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, astrologer_id INT NOT NULL, service_id INT NOT NULL, price NUMERIC(8, 2) NOT NULL, customer_email VARCHAR(50) NOT NULL, customer_name VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F529939856F716EE (astrologer_id), INDEX IDX_F5299398ED5CA9E6 (service_id), INDEX status (status_id), INDEX customer_email (customer_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status (id INT NOT NULL, name VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_B88F75C95E237E06 (name), INDEX name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986BF700BD FOREIGN KEY (status_id) REFERENCES order_status (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939856F716EE FOREIGN KEY (astrologer_id) REFERENCES astrologer (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX name ON service (name)');
        $this->addSql('CREATE INDEX email ON astrologer (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986BF700BD');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_status');
        $this->addSql('DROP INDEX email ON astrologer');
        $this->addSql('DROP INDEX name ON service');
    }
}

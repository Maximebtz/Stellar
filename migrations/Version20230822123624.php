<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822123624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lodge (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, type VARCHAR(50) NOT NULL, INDEX IDX_A11F814FD07ECCB6 (advert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lodge ADD CONSTRAINT FK_A11F814FD07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id)');
        $this->addSql('ALTER TABLE advert DROP key_method, DROP key_method_instruction');
        $this->addSql('ALTER TABLE reservation ADD firstname VARCHAR(25) NOT NULL, ADD surname VARCHAR(25) NOT NULL, ADD date_of_birth DATETIME NOT NULL, ADD address VARCHAR(100) NOT NULL, ADD cp VARCHAR(10) NOT NULL, ADD city VARCHAR(25) NOT NULL, ADD country VARCHAR(25) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lodge DROP FOREIGN KEY FK_A11F814FD07ECCB6');
        $this->addSql('DROP TABLE lodge');
        $this->addSql('ALTER TABLE advert ADD key_method VARCHAR(50) DEFAULT NULL, ADD key_method_instruction VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation DROP firstname, DROP surname, DROP date_of_birth, DROP address, DROP cp, DROP city, DROP country');
    }
}

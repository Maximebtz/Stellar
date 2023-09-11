<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911124037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advert_accessory (advert_id INT NOT NULL, accessory_id INT NOT NULL, INDEX IDX_3B8C3410D07ECCB6 (advert_id), INDEX IDX_3B8C341027E8CC78 (accessory_id), PRIMARY KEY(advert_id, accessory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advert_accessory ADD CONSTRAINT FK_3B8C3410D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_accessory ADD CONSTRAINT FK_3B8C341027E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40B27E8CC78');
        $this->addSql('DROP INDEX IDX_54F1F40B27E8CC78 ON advert');
        $this->addSql('ALTER TABLE advert DROP accessory_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert_accessory DROP FOREIGN KEY FK_3B8C3410D07ECCB6');
        $this->addSql('ALTER TABLE advert_accessory DROP FOREIGN KEY FK_3B8C341027E8CC78');
        $this->addSql('DROP TABLE advert_accessory');
        $this->addSql('ALTER TABLE advert ADD accessory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40B27E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_54F1F40B27E8CC78 ON advert (accessory_id)');
    }
}

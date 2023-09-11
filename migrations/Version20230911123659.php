<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911123659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advert_category (advert_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_84EEA340D07ECCB6 (advert_id), INDEX IDX_84EEA34012469DE2 (category_id), PRIMARY KEY(advert_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advert_category ADD CONSTRAINT FK_84EEA340D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_category ADD CONSTRAINT FK_84EEA34012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40B12469DE2');
        $this->addSql('DROP INDEX IDX_54F1F40B12469DE2 ON advert');
        $this->addSql('ALTER TABLE advert DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert_category DROP FOREIGN KEY FK_84EEA340D07ECCB6');
        $this->addSql('ALTER TABLE advert_category DROP FOREIGN KEY FK_84EEA34012469DE2');
        $this->addSql('DROP TABLE advert_category');
        $this->addSql('ALTER TABLE advert ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_54F1F40B12469DE2 ON advert (category_id)');
    }
}

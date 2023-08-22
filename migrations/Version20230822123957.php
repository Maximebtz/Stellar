<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822123957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert ADD lodge_id INT NOT NULL, DROP lodging_type');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40BB217AB93 FOREIGN KEY (lodge_id) REFERENCES lodge (id)');
        $this->addSql('CREATE INDEX IDX_54F1F40BB217AB93 ON advert (lodge_id)');
        $this->addSql('ALTER TABLE lodge DROP FOREIGN KEY FK_A11F814FD07ECCB6');
        $this->addSql('DROP INDEX IDX_A11F814FD07ECCB6 ON lodge');
        $this->addSql('ALTER TABLE lodge DROP advert_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40BB217AB93');
        $this->addSql('DROP INDEX IDX_54F1F40BB217AB93 ON advert');
        $this->addSql('ALTER TABLE advert ADD lodging_type VARCHAR(50) NOT NULL, DROP lodge_id');
        $this->addSql('ALTER TABLE lodge ADD advert_id INT NOT NULL');
        $this->addSql('ALTER TABLE lodge ADD CONSTRAINT FK_A11F814FD07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A11F814FD07ECCB6 ON lodge (advert_id)');
    }
}

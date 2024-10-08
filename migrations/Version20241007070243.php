<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007070243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accessory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advert (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, lodge_id INT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, cp VARCHAR(10) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) DEFAULT NULL, is_reported TINYINT(1) DEFAULT NULL, reported_by VARCHAR(255) DEFAULT NULL, INDEX IDX_54F1F40B7E3C61F9 (owner_id), INDEX IDX_54F1F40BB217AB93 (lodge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advert_category (advert_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_84EEA340D07ECCB6 (advert_id), INDEX IDX_84EEA34012469DE2 (category_id), PRIMARY KEY(advert_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advert_accessory (advert_id INT NOT NULL, accessory_id INT NOT NULL, INDEX IDX_3B8C3410D07ECCB6 (advert_id), INDEX IDX_3B8C341027E8CC78 (accessory_id), PRIMARY KEY(advert_id, accessory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, adverts_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6AC5A3D550 (adverts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodge (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, content LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FE92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, user_id INT NOT NULL, notation INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_480D45C2D07ECCB6 (advert_id), INDEX IDX_480D45C2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, user_id INT NOT NULL, arrival_date DATETIME NOT NULL, departure_date DATETIME NOT NULL, firstname VARCHAR(25) NOT NULL, surname VARCHAR(25) NOT NULL, address VARCHAR(100) NOT NULL, cp VARCHAR(10) NOT NULL, city VARCHAR(25) NOT NULL, country VARCHAR(25) NOT NULL, price DOUBLE PRECISION NOT NULL, status VARCHAR(20) DEFAULT NULL, INDEX IDX_42C84955D07ECCB6 (advert_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(20) NOT NULL, is_verified TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40BB217AB93 FOREIGN KEY (lodge_id) REFERENCES lodge (id)');
        $this->addSql('ALTER TABLE advert_category ADD CONSTRAINT FK_84EEA340D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_category ADD CONSTRAINT FK_84EEA34012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_accessory ADD CONSTRAINT FK_3B8C3410D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advert_accessory ADD CONSTRAINT FK_3B8C341027E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AC5A3D550 FOREIGN KEY (adverts_id) REFERENCES advert (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D07ECCB6 FOREIGN KEY (advert_id) REFERENCES advert (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40B7E3C61F9');
        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40BB217AB93');
        $this->addSql('ALTER TABLE advert_category DROP FOREIGN KEY FK_84EEA340D07ECCB6');
        $this->addSql('ALTER TABLE advert_category DROP FOREIGN KEY FK_84EEA34012469DE2');
        $this->addSql('ALTER TABLE advert_accessory DROP FOREIGN KEY FK_3B8C3410D07ECCB6');
        $this->addSql('ALTER TABLE advert_accessory DROP FOREIGN KEY FK_3B8C341027E8CC78');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AC5A3D550');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2D07ECCB6');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D07ECCB6');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('DROP TABLE accessory');
        $this->addSql('DROP TABLE advert');
        $this->addSql('DROP TABLE advert_category');
        $this->addSql('DROP TABLE advert_accessory');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE lodge');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

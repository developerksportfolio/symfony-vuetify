<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create url and click tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE url (
            id INT AUTO_INCREMENT NOT NULL,
            original_url VARCHAR(2048) NOT NULL,
            short_code VARCHAR(10) NOT NULL,
            title VARCHAR(255) DEFAULT NULL,
            is_active TINYINT(1) DEFAULT 1 NOT NULL,
            click_count INT DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            UNIQUE INDEX UNIQ_F47645AEF2E1E060 (short_code),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE click (
            id BIGINT AUTO_INCREMENT NOT NULL,
            url_id INT NOT NULL,
            clicked_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            ip_address VARCHAR(45) DEFAULT NULL,
            referrer VARCHAR(2048) DEFAULT NULL,
            user_agent LONGTEXT DEFAULT NULL,
            browser VARCHAR(100) DEFAULT NULL,
            browser_version VARCHAR(50) DEFAULT NULL,
            os VARCHAR(100) DEFAULT NULL,
            device_type VARCHAR(20) DEFAULT NULL,
            INDEX idx_click_url_date (url_id, clicked_at),
            PRIMARY KEY(id),
            CONSTRAINT FK_A48B82C881CFDAE7 FOREIGN KEY (url_id) REFERENCES url (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE click');
        $this->addSql('DROP TABLE url');
    }
}

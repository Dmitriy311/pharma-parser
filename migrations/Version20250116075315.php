<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116075315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, form VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, inn VARCHAR(255) NOT NULL, kpp VARCHAR(255) NOT NULL, mailing_address VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, purchase_number VARCHAR(255) NOT NULL, object_of_purchase VARCHAR(255) NOT NULL, initial_price VARCHAR(255) NOT NULL, eisposted_date VARCHAR(255) NOT NULL, epposted_date VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, protocol_name VARCHAR(255) NOT NULL, protocol_organization_name VARCHAR(255) NOT NULL, eanotice VARCHAR(255) NOT NULL, ealocation VARCHAR(255) NOT NULL, protocol_creation_date VARCHAR(255) NOT NULL, protocol_signing_date VARCHAR(255) NOT NULL, commission VARCHAR(255) NOT NULL, commission44_fzis_authorized VARCHAR(255) NOT NULL, commission_members_number VARCHAR(255) NOT NULL, commission_no_vote_members_number VARCHAR(255) NOT NULL, commission_present_members_number VARCHAR(255) NOT NULL, document_links LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participants_purchase (purchase_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_D9B4B821558FBEB9 (purchase_id), INDEX IDX_D9B4B8219D1C3019 (participant_id), PRIMARY KEY(purchase_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participants_purchase ADD CONSTRAINT FK_D9B4B821558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE participants_purchase ADD CONSTRAINT FK_D9B4B8219D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participants_purchase DROP FOREIGN KEY FK_D9B4B821558FBEB9');
        $this->addSql('ALTER TABLE participants_purchase DROP FOREIGN KEY FK_D9B4B8219D1C3019');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE participants_purchase');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220910173629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(70) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, INDEX IDX_FBD8E0F8217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_hobby (person_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_9552ECF3217BBB47 (person_id), INDEX IDX_9552ECF3322B2123 (hobby_id), PRIMARY KEY(person_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, rs VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_hobby ADD CONSTRAINT FK_9552ECF3217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_hobby ADD CONSTRAINT FK_9552ECF3322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person ADD profile_id INT DEFAULT NULL, DROP job');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176CCFA12B8 ON person (profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176CCFA12B8');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8217BBB47');
        $this->addSql('ALTER TABLE person_hobby DROP FOREIGN KEY FK_9552ECF3217BBB47');
        $this->addSql('ALTER TABLE person_hobby DROP FOREIGN KEY FK_9552ECF3322B2123');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE person_hobby');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP INDEX UNIQ_34DCD176CCFA12B8 ON person');
        $this->addSql('ALTER TABLE person ADD job VARCHAR(255) NOT NULL, DROP profile_id');
    }
}

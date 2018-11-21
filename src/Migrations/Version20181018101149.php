<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181018101149 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, hero LONGTEXT NOT NULL, hacks LONGTEXT NOT NULL, why LONGTEXT NOT NULL, next_year LONGTEXT NOT NULL, solo_link VARCHAR(255) NOT NULL, solo_badge INT NOT NULL, code_link VARCHAR(255) NOT NULL, code_badge INT NOT NULL, english VARCHAR(255) NOT NULL, last_diplome VARCHAR(255) NOT NULL, INDEX IDX_ECA209CDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jury (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, f_name VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, jury_id INT DEFAULT NULL, folder_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, note INT NOT NULL, INDEX IDX_CFBDFA14E560103C (jury_id), INDEX IDX_CFBDFA14162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secretary (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, f_name VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, secretary_id INT NOT NULL, name VARCHAR(255) NOT NULL, f_name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, age INT NOT NULL, adress VARCHAR(255) NOT NULL, town VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_8D93D649A2A63DB2 (secretary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14E560103C FOREIGN KEY (jury_id) REFERENCES jury (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A2A63DB2 FOREIGN KEY (secretary_id) REFERENCES secretary (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14162CB942');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14E560103C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A2A63DB2');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CDA76ED395');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE jury');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE secretary');
        $this->addSql('DROP TABLE user');
    }
}

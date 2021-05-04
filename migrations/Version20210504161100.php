<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504161100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, lit_id INT DEFAULT NULL, reservation_id INT NOT NULL, annee DATE NOT NULL, INDEX IDX_F4DD61D3278B5057 (lit_id), UNIQUE INDEX UNIQ_F4DD61D3B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, pavillon_id INT NOT NULL, numero VARCHAR(255) NOT NULL, nombrelit VARCHAR(255) DEFAULT NULL, INDEX IDX_C509E4FF8D2618A0 (pavillon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, faculte_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C1765B6372C3434F (faculte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT NOT NULL, niveau_id INT NOT NULL, num_identite VARCHAR(255) NOT NULL, date_naissance VARCHAR(255) NOT NULL, sexe VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, avatar LONGBLOB DEFAULT NULL, moyenne VARCHAR(255) NOT NULL, INDEX IDX_717E22E3B3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faculte (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lit (id INT AUTO_INCREMENT NOT NULL, chambre_id INT NOT NULL, quota_id INT NOT NULL, numero VARCHAR(255) NOT NULL, INDEX IDX_5DDB8E9D9B177F54 (chambre_id), INDEX IDX_5DDB8E9D54E2C62F (quota_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_4BDFF36BCCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pavillon (id INT AUTO_INCREMENT NOT NULL, campus_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_D5B8B380AF5D55E1 (campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quota_lit (id INT AUTO_INCREMENT NOT NULL, niveau_id INT NOT NULL, annee DATE NOT NULL, INDEX IDX_9D8A3991B3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT NOT NULL, annee DATE NOT NULL, INDEX IDX_42C84955DDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenoms VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3278B5057 FOREIGN KEY (lit_id) REFERENCES lit (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF8D2618A0 FOREIGN KEY (pavillon_id) REFERENCES pavillon (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6372C3434F FOREIGN KEY (faculte_id) REFERENCES faculte (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lit ADD CONSTRAINT FK_5DDB8E9D9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE lit ADD CONSTRAINT FK_5DDB8E9D54E2C62F FOREIGN KEY (quota_id) REFERENCES quota_lit (id)');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36BCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE pavillon ADD CONSTRAINT FK_D5B8B380AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE quota_lit ADD CONSTRAINT FK_9D8A3991B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pavillon DROP FOREIGN KEY FK_D5B8B380AF5D55E1');
        $this->addSql('ALTER TABLE lit DROP FOREIGN KEY FK_5DDB8E9D9B177F54');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36BCCF9E01E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955DDEAB1A3');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B6372C3434F');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3278B5057');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3B3E9C81');
        $this->addSql('ALTER TABLE quota_lit DROP FOREIGN KEY FK_9D8A3991B3E9C81');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF8D2618A0');
        $this->addSql('ALTER TABLE lit DROP FOREIGN KEY FK_5DDB8E9D54E2C62F');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3B83297E7');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3BF396750');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE faculte');
        $this->addSql('DROP TABLE lit');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE pavillon');
        $this->addSql('DROP TABLE quota_lit');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE `user`');
    }
}

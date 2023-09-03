<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902140015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contribution (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, user_id INT NOT NULL, description VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, type_contribution VARCHAR(255) NOT NULL, INDEX IDX_EA351E15C18272 (projet_id), INDEX IDX_EA351E15A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9CD3DE50E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flash_info (id INT AUTO_INCREMENT NOT NULL, maison_de_culte_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_F0CC3F04681021BB (maison_de_culte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maison_de_culte (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, responsable VARCHAR(255) NOT NULL, INDEX IDX_C6A8300CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, maison_de_culte_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_50159CA9681021BB (maison_de_culte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role_name VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, statique VARCHAR(255) NOT NULL, en_cours VARCHAR(255) NOT NULL, terminer VARCHAR(255) NOT NULL, suspendu VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E564F0BFC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE flash_info ADD CONSTRAINT FK_F0CC3F04681021BB FOREIGN KEY (maison_de_culte_id) REFERENCES maison_de_culte (id)');
        $this->addSql('ALTER TABLE maison_de_culte ADD CONSTRAINT FK_C6A8300CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9681021BB FOREIGN KEY (maison_de_culte_id) REFERENCES maison_de_culte (id)');
        $this->addSql('ALTER TABLE statut ADD CONSTRAINT FK_E564F0BFC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15C18272');
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15A76ED395');
        $this->addSql('ALTER TABLE flash_info DROP FOREIGN KEY FK_F0CC3F04681021BB');
        $this->addSql('ALTER TABLE maison_de_culte DROP FOREIGN KEY FK_C6A8300CA76ED395');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9681021BB');
        $this->addSql('ALTER TABLE statut DROP FOREIGN KEY FK_E564F0BFC18272');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE contribution');
        $this->addSql('DROP TABLE donateur');
        $this->addSql('DROP TABLE flash_info');
        $this->addSql('DROP TABLE maison_de_culte');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

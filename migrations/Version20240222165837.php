<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222165837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, information_categorie VARCHAR(511) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, titre_livre VARCHAR(255) NOT NULL, fichier_livre VARCHAR(255) NOT NULL, resume_livre VARCHAR(255) DEFAULT NULL, prix_livre DOUBLE PRECISION NOT NULL, date_upload_livre DATE NOT NULL, auteur_livre_id INT NOT NULL, categorie_livre_id INT NOT NULL, INDEX IDX_AC634F99ABF92DF (auteur_livre_id), INDEX IDX_AC634F99F5E64A1 (categorie_livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom_user VARCHAR(64) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99ABF92DF FOREIGN KEY (auteur_livre_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99F5E64A1 FOREIGN KEY (categorie_livre_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99ABF92DF');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99F5E64A1');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE user');
    }
}

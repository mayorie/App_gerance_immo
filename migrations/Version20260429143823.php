<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260429143823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__garants_physiques AS SELECT id, nom, prenom, tel, mail, adresse, code_postal, ville, date_de_naissance, lieu_de_naissance, garants_id_id FROM garants_physiques');
        $this->addSql('DROP TABLE garants_physiques');
        $this->addSql('CREATE TABLE garants_physiques (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) DEFAULT NULL, ville VARCHAR(32) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, garants_id_id INTEGER NOT NULL, CONSTRAINT FK_8DC9138872EDE21D FOREIGN KEY (garants_id_id) REFERENCES garants (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO garants_physiques (id, nom, prenom, tel, mail, adresse, code_postal, ville, date_de_naissance, lieu_de_naissance, garants_id_id) SELECT id, nom, prenom, tel, mail, adresse, code_postal, ville, date_de_naissance, lieu_de_naissance, garants_id_id FROM __temp__garants_physiques');
        $this->addSql('DROP TABLE __temp__garants_physiques');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8DC9138872EDE21D ON garants_physiques (garants_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__garants_physiques AS SELECT id, nom, prenom, tel, mail, adresse, code_postal, ville, date_de_naissance, lieu_de_naissance, garants_id_id FROM garants_physiques');
        $this->addSql('DROP TABLE garants_physiques');
        $this->addSql('CREATE TABLE garants_physiques (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel INTEGER DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal INTEGER DEFAULT NULL, ville VARCHAR(32) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, garants_id_id INTEGER NOT NULL, CONSTRAINT FK_8DC9138872EDE21D FOREIGN KEY (garants_id_id) REFERENCES garants (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO garants_physiques (id, nom, prenom, tel, mail, adresse, code_postal, ville, date_de_naissance, lieu_de_naissance, garants_id_id) SELECT id, nom, prenom, tel, mail, adresse, code_postal, ville, date_de_naissance, lieu_de_naissance, garants_id_id FROM __temp__garants_physiques');
        $this->addSql('DROP TABLE __temp__garants_physiques');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8DC9138872EDE21D ON garants_physiques (garants_id_id)');
    }
}

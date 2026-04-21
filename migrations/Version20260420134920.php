<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260420134920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, texte VARCHAR(512) NOT NULL, logements_id_id INTEGER DEFAULT NULL, locataires_id_id INTEGER DEFAULT NULL, CONSTRAINT FK_D9BEC0C4402BDBA8 FOREIGN KEY (logements_id_id) REFERENCES logements (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D9BEC0C4333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D9BEC0C4402BDBA8 ON commentaires (logements_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D9BEC0C4333BAD6 ON commentaires (locataires_id_id)');
        $this->addSql('CREATE TABLE garants (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(3) NOT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_D04B287D333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D04B287D333BAD6 ON garants (locataires_id_id)');
        $this->addSql('CREATE TABLE garants_physiques (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel INTEGER DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal INTEGER DEFAULT NULL, ville VARCHAR(32) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, garants_id_id INTEGER NOT NULL, CONSTRAINT FK_8DC9138872EDE21D FOREIGN KEY (garants_id_id) REFERENCES garants (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8DC9138872EDE21D ON garants_physiques (garants_id_id)');
        $this->addSql('CREATE TABLE garants_visale (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, texte VARCHAR(32) DEFAULT NULL, date_anniversaire DATE DEFAULT NULL, garants_id_id INTEGER NOT NULL, CONSTRAINT FK_AA9044A872EDE21D FOREIGN KEY (garants_id_id) REFERENCES garants (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA9044A872EDE21D ON garants_visale (garants_id_id)');
        $this->addSql('CREATE TABLE locataires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel INTEGER DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, debut_bail DATE DEFAULT NULL, montant_caution DOUBLE PRECISION DEFAULT NULL, loyer_tcc DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu DOUBLE PRECISION DEFAULT NULL, date_edl_entree DATE DEFAULT NULL, preavis_recu_le DATE DEFAULT NULL, debut_du_preavis DATE DEFAULT NULL, date_edl_sortie DATE DEFAULT NULL, date_de_sortie DATE DEFAULT NULL, montant_solde_de_tout_compte INTEGER DEFAULT NULL, date_solde_de_tout_compte DATE DEFAULT NULL, mode_paiement_solde_de_tout_compte VARCHAR(3) DEFAULT NULL, banque_solde_de_tout_compte VARCHAR(32) DEFAULT NULL, cloture_contrat_visale BOOLEAN DEFAULT NULL, a_quitte_le_logement BOOLEAN DEFAULT NULL, logements_id_id INTEGER NOT NULL, CONSTRAINT FK_2C12880D402BDBA8 FOREIGN KEY (logements_id_id) REFERENCES logements (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2C12880D402BDBA8 ON locataires (logements_id_id)');
        $this->addSql('CREATE TABLE logements (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_appart VARCHAR(32) DEFAULT NULL, residence VARCHAR(128) DEFAULT NULL, batiment VARCHAR(32) DEFAULT NULL, appt VARCHAR(32) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal SMALLINT DEFAULT NULL, ville VARCHAR(32) DEFAULT NULL, siret VARCHAR(32) DEFAULT NULL, num_chambre SMALLINT DEFAULT NULL)');
        $this->addSql('CREATE TABLE loyers_hc (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, date_mes DATE DEFAULT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_90BBDB05333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_90BBDB05333BAD6 ON loyers_hc (locataires_id_id)');
        $this->addSql('CREATE TABLE packs_services (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, date_mes DATE DEFAULT NULL, montant_regularisation DOUBLE PRECISION DEFAULT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_620DE8E1333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_620DE8E1333BAD6 ON packs_services (locataires_id_id)');
        $this->addSql('CREATE TABLE paiements_mensuels (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE DEFAULT NULL, part_recue_du_locataire_date DATE DEFAULT NULL, part_recue_du_locataire_mode VARCHAR(3) DEFAULT NULL, part_recue_du_locataire_montant DOUBLE PRECISION NOT NULL, part_recue_de_la_caf_date DATE DEFAULT NULL, part_recue_de_la_caf_mode VARCHAR(3) DEFAULT NULL, part_recue_de_la_caf_montant DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu_fin_de_mois DOUBLE PRECISION DEFAULT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_D4107848333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D4107848333BAD6 ON paiements_mensuels (locataires_id_id)');
        $this->addSql('CREATE TABLE provisions_pour_charges (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, date_mes DATE DEFAULT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_F3E0D2ED333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F3E0D2ED333BAD6 ON provisions_pour_charges (locataires_id_id)');
        $this->addSql('CREATE TABLE rbtbailleur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, motif VARCHAR(128) DEFAULT NULL, date DATE DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, mode VARCHAR(3) DEFAULT NULL, paiements_mensuel_id_id INTEGER NOT NULL, CONSTRAINT FK_AA0DE4D03050638A FOREIGN KEY (paiements_mensuel_id_id) REFERENCES paiements_mensuels (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA0DE4D03050638A ON rbtbailleur (paiements_mensuel_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE garants');
        $this->addSql('DROP TABLE garants_physiques');
        $this->addSql('DROP TABLE garants_visale');
        $this->addSql('DROP TABLE locataires');
        $this->addSql('DROP TABLE logements');
        $this->addSql('DROP TABLE loyers_hc');
        $this->addSql('DROP TABLE packs_services');
        $this->addSql('DROP TABLE paiements_mensuels');
        $this->addSql('DROP TABLE provisions_pour_charges');
        $this->addSql('DROP TABLE rbtbailleur');
    }
}

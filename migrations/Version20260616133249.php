<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616133249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__locataires AS SELECT id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, logements_id_id, statut, num_comptable FROM locataires');
        $this->addSql('DROP TABLE locataires');
        $this->addSql('CREATE TABLE locataires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel INTEGER DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, debut_bail DATE DEFAULT NULL, montant_caution DOUBLE PRECISION DEFAULT NULL, loyer_tcc DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu DOUBLE PRECISION DEFAULT NULL, date_edl_entree DATE DEFAULT NULL, preavis_recu_le DATE DEFAULT NULL, debut_du_preavis DATE DEFAULT NULL, date_edl_sortie DATE DEFAULT NULL, date_de_sortie DATE DEFAULT NULL, montant_solde_de_tout_compte DOUBLE PRECISION DEFAULT NULL, date_solde_de_tout_compte DATE DEFAULT NULL, mode_paiement_solde_de_tout_compte VARCHAR(3) DEFAULT NULL, banque_solde_de_tout_compte VARCHAR(32) DEFAULT NULL, cloture_contrat_visale BOOLEAN DEFAULT NULL, a_quitte_le_logement BOOLEAN DEFAULT NULL, logements_id_id INTEGER NOT NULL, statut VARCHAR(32) DEFAULT NULL, num_comptable INTEGER DEFAULT NULL, CONSTRAINT FK_2C12880D402BDBA8 FOREIGN KEY (logements_id_id) REFERENCES logements (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO locataires (id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, logements_id_id, statut, num_comptable) SELECT id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, logements_id_id, statut, num_comptable FROM __temp__locataires');
        $this->addSql('DROP TABLE __temp__locataires');
        $this->addSql('CREATE INDEX IDX_2C12880D402BDBA8 ON locataires (logements_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__paiements_mensuels AS SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, locataires_id_id, regul_packs_services, regul_provisions_pour_charges FROM paiements_mensuels');
        $this->addSql('DROP TABLE paiements_mensuels');
        $this->addSql('CREATE TABLE paiements_mensuels (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME DEFAULT NULL, part_recue_du_locataire_date DATE DEFAULT NULL, part_recue_du_locataire_mode VARCHAR(3) DEFAULT NULL, part_recue_du_locataire_montant DOUBLE PRECISION DEFAULT NULL, part_recue_de_la_caf_date DATE DEFAULT NULL, part_recue_de_la_caf_mode VARCHAR(3) DEFAULT NULL, part_recue_de_la_caf_montant DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu_fin_de_mois DOUBLE PRECISION DEFAULT NULL, locataires_id_id INTEGER NOT NULL, regul_packs_services DOUBLE PRECISION DEFAULT NULL, regul_provisions_pour_charges DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_D4107848333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paiements_mensuels (id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, locataires_id_id, regul_packs_services, regul_provisions_pour_charges) SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, locataires_id_id, regul_packs_services, regul_provisions_pour_charges FROM __temp__paiements_mensuels');
        $this->addSql('DROP TABLE __temp__paiements_mensuels');
        $this->addSql('CREATE INDEX IDX_D4107848333BAD6 ON paiements_mensuels (locataires_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__locataires AS SELECT id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, statut, num_comptable, logements_id_id FROM locataires');
        $this->addSql('DROP TABLE locataires');
        $this->addSql('CREATE TABLE locataires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel INTEGER DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, debut_bail DATE DEFAULT NULL, montant_caution DOUBLE PRECISION DEFAULT NULL, loyer_tcc DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu DOUBLE PRECISION DEFAULT NULL, date_edl_entree DATE DEFAULT NULL, preavis_recu_le DATE DEFAULT NULL, debut_du_preavis DATE DEFAULT NULL, date_edl_sortie DATE DEFAULT NULL, date_de_sortie DATE DEFAULT NULL, montant_solde_de_tout_compte INTEGER DEFAULT NULL, date_solde_de_tout_compte DATE DEFAULT NULL, mode_paiement_solde_de_tout_compte VARCHAR(3) DEFAULT NULL, banque_solde_de_tout_compte VARCHAR(32) DEFAULT NULL, cloture_contrat_visale BOOLEAN DEFAULT NULL, a_quitte_le_logement BOOLEAN DEFAULT NULL, statut VARCHAR(32) DEFAULT NULL, num_comptable INTEGER DEFAULT NULL, logements_id_id INTEGER NOT NULL, CONSTRAINT FK_2C12880D402BDBA8 FOREIGN KEY (logements_id_id) REFERENCES logements (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO locataires (id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, statut, num_comptable, logements_id_id) SELECT id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, statut, num_comptable, logements_id_id FROM __temp__locataires');
        $this->addSql('DROP TABLE __temp__locataires');
        $this->addSql('CREATE INDEX IDX_2C12880D402BDBA8 ON locataires (logements_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__paiements_mensuels AS SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, regul_packs_services, regul_provisions_pour_charges, locataires_id_id FROM paiements_mensuels');
        $this->addSql('DROP TABLE paiements_mensuels');
        $this->addSql('CREATE TABLE paiements_mensuels (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE DEFAULT NULL, part_recue_du_locataire_date DATE DEFAULT NULL, part_recue_du_locataire_mode VARCHAR(3) DEFAULT NULL, part_recue_du_locataire_montant DOUBLE PRECISION DEFAULT NULL, part_recue_de_la_caf_date DATE DEFAULT NULL, part_recue_de_la_caf_mode VARCHAR(3) DEFAULT NULL, part_recue_de_la_caf_montant DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu_fin_de_mois DOUBLE PRECISION DEFAULT NULL, regul_packs_services DOUBLE PRECISION DEFAULT NULL, regul_provisions_pour_charges DOUBLE PRECISION DEFAULT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_D4107848333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paiements_mensuels (id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, regul_packs_services, regul_provisions_pour_charges, locataires_id_id) SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, regul_packs_services, regul_provisions_pour_charges, locataires_id_id FROM __temp__paiements_mensuels');
        $this->addSql('DROP TABLE __temp__paiements_mensuels');
        $this->addSql('CREATE INDEX IDX_D4107848333BAD6 ON paiements_mensuels (locataires_id_id)');
    }
}

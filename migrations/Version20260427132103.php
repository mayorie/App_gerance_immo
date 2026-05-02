<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260427132103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locataires ADD COLUMN num_comptable INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__locataires AS SELECT id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, statut, logements_id_id FROM locataires');
        $this->addSql('DROP TABLE locataires');
        $this->addSql('CREATE TABLE locataires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(128) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, tel INTEGER DEFAULT NULL, mail VARCHAR(128) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, lieu_de_naissance VARCHAR(64) DEFAULT NULL, debut_bail DATE DEFAULT NULL, montant_caution DOUBLE PRECISION DEFAULT NULL, loyer_tcc DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu DOUBLE PRECISION DEFAULT NULL, date_edl_entree DATE DEFAULT NULL, preavis_recu_le DATE DEFAULT NULL, debut_du_preavis DATE DEFAULT NULL, date_edl_sortie DATE DEFAULT NULL, date_de_sortie DATE DEFAULT NULL, montant_solde_de_tout_compte INTEGER DEFAULT NULL, date_solde_de_tout_compte DATE DEFAULT NULL, mode_paiement_solde_de_tout_compte VARCHAR(3) DEFAULT NULL, banque_solde_de_tout_compte VARCHAR(32) DEFAULT NULL, cloture_contrat_visale BOOLEAN DEFAULT NULL, a_quitte_le_logement BOOLEAN DEFAULT NULL, statut VARCHAR(32) DEFAULT NULL, logements_id_id INTEGER NOT NULL, CONSTRAINT FK_2C12880D402BDBA8 FOREIGN KEY (logements_id_id) REFERENCES logements (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO locataires (id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, statut, logements_id_id) SELECT id, nom, prenom, tel, mail, date_de_naissance, lieu_de_naissance, debut_bail, montant_caution, loyer_tcc, restant_du_trop_percu, date_edl_entree, preavis_recu_le, debut_du_preavis, date_edl_sortie, date_de_sortie, montant_solde_de_tout_compte, date_solde_de_tout_compte, mode_paiement_solde_de_tout_compte, banque_solde_de_tout_compte, cloture_contrat_visale, a_quitte_le_logement, statut, logements_id_id FROM __temp__locataires');
        $this->addSql('DROP TABLE __temp__locataires');
        $this->addSql('CREATE INDEX IDX_2C12880D402BDBA8 ON locataires (logements_id_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260506085102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paiements_mensuels AS SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, locataires_id_id, regul_packs_services, regul_provisions_pour_charges FROM paiements_mensuels');
        $this->addSql('DROP TABLE paiements_mensuels');
        $this->addSql('CREATE TABLE paiements_mensuels (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE DEFAULT NULL, part_recue_du_locataire_date DATE DEFAULT NULL, part_recue_du_locataire_mode VARCHAR(3) DEFAULT NULL, part_recue_du_locataire_montant DOUBLE PRECISION DEFAULT NULL, part_recue_de_la_caf_date DATE DEFAULT NULL, part_recue_de_la_caf_mode VARCHAR(3) DEFAULT NULL, part_recue_de_la_caf_montant DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu_fin_de_mois DOUBLE PRECISION DEFAULT NULL, locataires_id_id INTEGER NOT NULL, regul_packs_services DOUBLE PRECISION DEFAULT NULL, regul_provisions_pour_charges DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_D4107848333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paiements_mensuels (id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, locataires_id_id, regul_packs_services, regul_provisions_pour_charges) SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, locataires_id_id, regul_packs_services, regul_provisions_pour_charges FROM __temp__paiements_mensuels');
        $this->addSql('DROP TABLE __temp__paiements_mensuels');
        $this->addSql('CREATE INDEX IDX_D4107848333BAD6 ON paiements_mensuels (locataires_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paiements_mensuels AS SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, regul_packs_services, regul_provisions_pour_charges, locataires_id_id FROM paiements_mensuels');
        $this->addSql('DROP TABLE paiements_mensuels');
        $this->addSql('CREATE TABLE paiements_mensuels (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE DEFAULT NULL, part_recue_du_locataire_date DATE DEFAULT NULL, part_recue_du_locataire_mode VARCHAR(3) DEFAULT NULL, part_recue_du_locataire_montant DOUBLE PRECISION NOT NULL, part_recue_de_la_caf_date DATE DEFAULT NULL, part_recue_de_la_caf_mode VARCHAR(3) DEFAULT NULL, part_recue_de_la_caf_montant DOUBLE PRECISION DEFAULT NULL, restant_du_trop_percu_fin_de_mois DOUBLE PRECISION DEFAULT NULL, regul_packs_services DOUBLE PRECISION DEFAULT NULL, regul_provisions_pour_charges DOUBLE PRECISION DEFAULT NULL, locataires_id_id INTEGER NOT NULL, CONSTRAINT FK_D4107848333BAD6 FOREIGN KEY (locataires_id_id) REFERENCES locataires (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paiements_mensuels (id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, regul_packs_services, regul_provisions_pour_charges, locataires_id_id) SELECT id, date, part_recue_du_locataire_date, part_recue_du_locataire_mode, part_recue_du_locataire_montant, part_recue_de_la_caf_date, part_recue_de_la_caf_mode, part_recue_de_la_caf_montant, restant_du_trop_percu_fin_de_mois, regul_packs_services, regul_provisions_pour_charges, locataires_id_id FROM __temp__paiements_mensuels');
        $this->addSql('DROP TABLE __temp__paiements_mensuels');
        $this->addSql('CREATE INDEX IDX_D4107848333BAD6 ON paiements_mensuels (locataires_id_id)');
    }
}

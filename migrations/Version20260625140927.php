<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260625140927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__facture_fourn AS SELECT id, date_facture, fournisseur, motif, montant, date_paiement, mode, pcg_id FROM facture_fourn');
        $this->addSql('DROP TABLE facture_fourn');
        $this->addSql('CREATE TABLE facture_fourn (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_facture DATE NOT NULL, fournisseur VARCHAR(255) NOT NULL, motif VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATE NOT NULL, mode VARCHAR(255) NOT NULL, pcg_id INTEGER NOT NULL, montant2 DOUBLE PRECISION DEFAULT NULL, montant_paiement DOUBLE PRECISION DEFAULT NULL, pcg2_id INTEGER DEFAULT NULL, CONSTRAINT FK_C418E7825B837267 FOREIGN KEY (pcg_id) REFERENCES pcg (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C418E7821754F5F4 FOREIGN KEY (pcg2_id) REFERENCES pcg (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO facture_fourn (id, date_facture, fournisseur, motif, montant, date_paiement, mode, pcg_id) SELECT id, date_facture, fournisseur, motif, montant, date_paiement, mode, pcg_id FROM __temp__facture_fourn');
        $this->addSql('DROP TABLE __temp__facture_fourn');
        $this->addSql('CREATE INDEX IDX_C418E7825B837267 ON facture_fourn (pcg_id)');
        $this->addSql('CREATE INDEX IDX_C418E7821754F5F4 ON facture_fourn (pcg2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__facture_fourn AS SELECT id, date_facture, fournisseur, motif, montant, date_paiement, mode, pcg_id FROM facture_fourn');
        $this->addSql('DROP TABLE facture_fourn');
        $this->addSql('CREATE TABLE facture_fourn (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_facture DATE NOT NULL, fournisseur VARCHAR(255) NOT NULL, motif VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATE NOT NULL, mode VARCHAR(255) NOT NULL, pcg_id INTEGER NOT NULL, CONSTRAINT FK_C418E7825B837267 FOREIGN KEY (pcg_id) REFERENCES pcg (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO facture_fourn (id, date_facture, fournisseur, motif, montant, date_paiement, mode, pcg_id) SELECT id, date_facture, fournisseur, motif, montant, date_paiement, mode, pcg_id FROM __temp__facture_fourn');
        $this->addSql('DROP TABLE __temp__facture_fourn');
        $this->addSql('CREATE INDEX IDX_C418E7825B837267 ON facture_fourn (pcg_id)');
    }
}

<?php

namespace App\DataFixtures\Data_test;

use App\Entity\Logements;
use App\Entity\Locataires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LocatairesSeedTest extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['test'];
    }

    public function getDependencies(): array
    {
        return [
            LogementsSeedTest::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $soleil_ch1 = $this->getReference('logement_soleil_ch1', Logements::class);
        $soleil_ch2 = $this->getReference('logement_soleil_ch2', Logements::class);
        $soleil_ch3 = $this->getReference('logement_soleil_ch3', Logements::class);
        $etoile = $this->getReference('logement_etoile', Logements::class);
        $lune = $this->getReference('logement_lune', Logements::class);
        $ronsard_60_ch1 = $this->getReference('logement_ronsard_60_ch1', Logements::class);
        $ronsard_60_ch2 = $this->getReference('logement_ronsard_60_ch2', Logements::class);
        $ronsard_60_ch3 = $this->getReference('logement_ronsard_60_ch3', Logements::class);
        $ronsard_64_ch1 = $this->getReference('logement_ronsard_64_ch1', Logements::class);
        $ronsard_64_ch2 = $this->getReference('logement_ronsard_64_ch2', Logements::class);
        $ronsard_64_ch3 = $this->getReference('logement_ronsard_64_ch3', Logements::class);

        $locataires = [
            
            #Locataire tjs actif dans studio lune
            [
                'nom' => 'Buche-Gatignol',
                'prenom' => 'Marcel',
                'tel' => '0612345678',
                'mail' => 'marcel.buche-gatignol@example.com',
                'date_de_naissance' => new \DateTime('1990-05-14'),
                'lieu_de_naissance' => 'Paris',
                'Statut'=>'chomage',
                'LogementsID' =>  $lune,
                'debut_bail' => new \DateTime('2024-09-01'),
                'montant_caution' => 850,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2024-09-01'),
                'preavis_recu_le' => null,
                'debut_du_preavis' => null,
                'date_EDL_sortie' => null,
                'date_de_sortie' => null,
                'montant_solde_de_tout_compte' => null,
                'date_solde_de_tout_compte' => null,
                'mode_paiement_solde_de_tout_compte' => null,
                'banque_solde_de_tout_compte' => null,
                'cloture_contrat_visale' => false,
                'a_quitte_le_logement' => false,
                # commentaires
                #'Provisions_pour_charges' => 60,
                #'Loyers_HC' => 790,
                #'Packs_services' => 50,
                # Garants
                # Paiements_mensuels
            ],
            
            #Locataire ayant quitté le studio lune
            [
                'nom' => 'MARTIN',
                'prenom' => 'Jean',
                'tel' => '0634345678',
                'mail' => 'jean.martin@example.com',
                'date_de_naissance' => new \DateTime('1997-05-15'),
                'lieu_de_naissance' => 'Brest',
                'Statut'=>'etudiant',
                'LogementsID' =>  $lune,
                'debut_bail' => new \DateTime('2022-10-15'),
                'montant_caution' => 850,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2022-10-19'),
                'preavis_recu_le' => new \DateTime('2024-07-15'),
                'debut_du_preavis' => new \DateTime('2024-08-01'),
                'date_EDL_sortie' => new \DateTime('2024-09-01'),
                'date_de_sortie' => new \DateTime('2024-09-01'),
                'montant_solde_de_tout_compte' => -129,
                'date_solde_de_tout_compte' => new \DateTime('2024-10-01'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'BNP',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],

            # 2SD Locataire ayant quitté le studio lune
            [
                'nom' => 'DURAND',
                'prenom' => 'Camille',
                'tel' => '0678123456',
                'mail' => 'camille.durand@example.com',
                'date_de_naissance' => new \DateTime('1999-11-23'),
                'lieu_de_naissance' => 'Lyon',
                'Statut'=>'etudiant',
                'LogementsID' =>  $lune,
                'debut_bail' => new \DateTime('2022-03-15'),
                'montant_caution' => 920,
                # loyer_TCC
                'restant_du_trop_percu' => 45,
                'date_EDL_entree' => new \DateTime('2022-03-15'),
                'preavis_recu_le' => new \DateTime('2022-09-15'),
                'debut_du_preavis' => new \DateTime('2022-09-15'),
                'date_EDL_sortie' => new \DateTime('2022-10-15'),
                'date_de_sortie' => new \DateTime('2022-10-15'),
                'montant_solde_de_tout_compte' => 78,
                'date_solde_de_tout_compte' => new \DateTime('2022-11-15'),
                'mode_paiement_solde_de_tout_compte' => 'ESP',
                'banque_solde_de_tout_compte' => 'Credit Agricole',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],

            #Locataire tjs actif dans studio etoile
            [
                'nom' => 'LEROY',
                'prenom' => 'Nina',
                'tel' => '0622334455',
                'mail' => 'nina.leroy@example.com',
                'date_de_naissance' => new \DateTime('2001-02-08'),
                'lieu_de_naissance' => 'Tours',
                'Statut'=>'etudiant',
                'LogementsID' =>  $etoile,
                'debut_bail' => new \DateTime('2025-01-10'),
                'montant_caution' => 900,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2025-01-10'),
                'preavis_recu_le' => null,
                'debut_du_preavis' => null,
                'date_EDL_sortie' => null,
                'date_de_sortie' => null,
                'montant_solde_de_tout_compte' => null,
                'date_solde_de_tout_compte' => null,
                'mode_paiement_solde_de_tout_compte' => null,
                'banque_solde_de_tout_compte' => null,
                'cloture_contrat_visale' => false,
                'a_quitte_le_logement' => false,
            ],
            
            #Locataire ayant quitté le studio etoile
            [
                'nom' => 'MOREAU',
                'prenom' => 'Hugo',
                'tel' => '0655667788',
                'mail' => 'hugo.moreau@example.com',
                'date_de_naissance' => new \DateTime('1996-09-30'),
                'lieu_de_naissance' => 'Rouen',
                'Statut'=>'etudiant',
                'LogementsID' =>  $etoile,
                'debut_bail' => new \DateTime('2023-06-01'),
                'montant_caution' => 880,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2023-06-03'),
                'preavis_recu_le' => new \DateTime('2024-11-05'),
                'debut_du_preavis' => new \DateTime('2024-12-01'),
                'date_EDL_sortie' => new \DateTime('2025-01-02'),
                'date_de_sortie' => new \DateTime('2025-01-02'),
                'montant_solde_de_tout_compte' => -52,
                'date_solde_de_tout_compte' => new \DateTime('2025-02-01'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'Societe Generale',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],
            
            # 2SD Locataire ayant quitté le studio etoile
            [
                'nom' => 'GARNIER',
                'prenom' => 'Sarah',
                'tel' => '0601020304',
                'mail' => 'sarah.garnier@example.com',
                'date_de_naissance' => new \DateTime('1998-04-12'),
                'lieu_de_naissance' => 'Montpellier',
                'Statut'=>'etudiant',
                'LogementsID' =>  $etoile,
                'debut_bail' => new \DateTime('2021-06-01'),
                'montant_caution' => 910,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2021-06-04'),
                'preavis_recu_le' => new \DateTime('2023-03-20'),
                'debut_du_preavis' => new \DateTime('2023-04-01'),
                'date_EDL_sortie' => new \DateTime('2023-05-02'),
                'date_de_sortie' => new \DateTime('2023-05-02'),
                'montant_solde_de_tout_compte' => 120,
                'date_solde_de_tout_compte' => new \DateTime('2023-06-01'),
                'mode_paiement_solde_de_tout_compte' => 'ESP',
                'banque_solde_de_tout_compte' => 'Banque Populaire',
                'cloture_contrat_visale' => false,
                'a_quitte_le_logement' => true,
            ],
            #Locataire tjs actif dans la COLOC SOLEIL (CH1)
            [
                'nom' => 'PETIT',
                'prenom' => 'Amina',
                'tel' => '0699010203',
                'mail' => 'amina.petit@example.com',
                'date_de_naissance' => new \DateTime('2000-07-19'),
                'lieu_de_naissance' => 'Reims',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch1,
                'debut_bail' => new \DateTime('2024-09-15'),
                'montant_caution' => 940,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2024-09-16'),
                'preavis_recu_le' => null,
                'debut_du_preavis' => null,
                'date_EDL_sortie' => null,
                'date_de_sortie' => null,
                'montant_solde_de_tout_compte' => null,
                'date_solde_de_tout_compte' => null,
                'mode_paiement_solde_de_tout_compte' => null,
                'banque_solde_de_tout_compte' => null,
                'cloture_contrat_visale' => false,
                'a_quitte_le_logement' => false,
            ],
            
            #Locataire ayant quitté la COLOC SOLEIL (CH1)
            [
                'nom' => 'ROUSSEAU',
                'prenom' => 'Lucas',
                'tel' => '0611223344',
                'mail' => 'lucas.rousseau@example.com',
                'date_de_naissance' => new \DateTime('1995-12-03'),
                'lieu_de_naissance' => 'Dijon',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch1,
                'debut_bail' => new \DateTime('2022-06-01'),
                'montant_caution' => 870,
                # loyer_TCC
                'restant_du_trop_percu' => 15,
                'date_EDL_entree' => new \DateTime('2022-06-03'),
                'preavis_recu_le' => new \DateTime('2024-06-10'),
                'debut_du_preavis' => new \DateTime('2024-07-01'),
                'date_EDL_sortie' => new \DateTime('2024-08-31'),
                'date_de_sortie' => new \DateTime('2024-08-31'),
                'montant_solde_de_tout_compte' => 35,
                'date_solde_de_tout_compte' => new \DateTime('2024-09-20'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'CIC',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],
            
            # 2SD Locataire ayant quitté la COLOC SOLEIL (CH1)
            [
                'nom' => 'BERNARD',
                'prenom' => 'Chloe',
                'tel' => '0670708090',
                'mail' => 'chloe.bernard@example.com',
                'date_de_naissance' => new \DateTime('1999-03-27'),
                'lieu_de_naissance' => 'Angers',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch1,
                'debut_bail' => new \DateTime('2021-01-15'),
                'montant_caution' => 860,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2021-01-18'),
                'preavis_recu_le' => new \DateTime('2022-04-05'),
                'debut_du_preavis' => new \DateTime('2022-05-01'),
                'date_EDL_sortie' => new \DateTime('2022-05-31'),
                'date_de_sortie' => new \DateTime('2022-05-31'),
                'montant_solde_de_tout_compte' => -18,
                'date_solde_de_tout_compte' => new \DateTime('2022-06-20'),
                'mode_paiement_solde_de_tout_compte' => 'ESP',
                'banque_solde_de_tout_compte' => 'LCL',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],
            # 2SD Locataire ayant quitté la COLOC SOLEIL (CH2)
            [
                'nom' => 'LEGRAND',
                'prenom' => 'Emma',
                'tel' => '0613141516',
                'mail' => 'emma.legrand@example.com',
                'date_de_naissance' => new \DateTime('1998-08-22'),
                'lieu_de_naissance' => 'Limoges',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch2,
                'debut_bail' => new \DateTime('2021-10-01'),
                'montant_caution' => 875,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2021-10-02'),
                'preavis_recu_le' => new \DateTime('2022-08-12'),
                'debut_du_preavis' => new \DateTime('2022-09-01'),
                'date_EDL_sortie' => new \DateTime('2022-09-30'),
                'date_de_sortie' => new \DateTime('2022-09-30'),
                'montant_solde_de_tout_compte' => 15,
                'date_solde_de_tout_compte' => new \DateTime('2022-10-20'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'Credit Mutuel',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],

            #Locataire ayant quitté la COLOC SOLEIL (CH2)
            [
                'nom' => 'MARCHAND',
                'prenom' => 'Yanis',
                'tel' => '0677889900',
                'mail' => 'yanis.marchand@example.com',
                'date_de_naissance' => new \DateTime('1997-02-14'),
                'lieu_de_naissance' => 'Clermont-Ferrand',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch2,
                'debut_bail' => new \DateTime('2022-10-10'),
                'montant_caution' => 910,
                # loyer_TCC
                'restant_du_trop_percu' => 40,
                'date_EDL_entree' => new \DateTime('2022-10-12'),
                'preavis_recu_le' => new \DateTime('2024-02-05'),
                'debut_du_preavis' => new \DateTime('2024-03-01'),
                'date_EDL_sortie' => new \DateTime('2024-03-31'),
                'date_de_sortie' => new \DateTime('2024-03-31'),
                'montant_solde_de_tout_compte' => -65,
                'date_solde_de_tout_compte' => new \DateTime('2024-04-25'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'Hello bank!',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],

            #Locataire tjs actif dans la COLOC SOLEIL (CH2)
            [
                'nom' => 'GARCIAS',
                'prenom' => 'Noah',
                'tel' => '0602030405',
                'mail' => 'noah.garcias@example.com',
                'date_de_naissance' => new \DateTime('2001-11-09'),
                'lieu_de_naissance' => 'Nice',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch2,
                'debut_bail' => new \DateTime('2024-04-15'),
                'montant_caution' => 960,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2024-04-16'),
                'preavis_recu_le' => null,
                'debut_du_preavis' => null,
                'date_EDL_sortie' => null,
                'date_de_sortie' => null,
                'montant_solde_de_tout_compte' => null,
                'date_solde_de_tout_compte' => null,
                'mode_paiement_solde_de_tout_compte' => null,
                'banque_solde_de_tout_compte' => null,
                'cloture_contrat_visale' => false,
                'a_quitte_le_logement' => false,
            ],
            #Locataire tjs actif dans la COLOC SOLEIL (CH3)
            [
                'nom' => 'CARON',
                'prenom' => 'Ines',
                'tel' => '0639394041',
                'mail' => 'ines.caron@example.com',
                'date_de_naissance' => new \DateTime('2002-05-26'),
                'lieu_de_naissance' => 'Strasbourg',
                'Statut'=>'etudiant',
                'LogementsID' =>  $soleil_ch3,
                'debut_bail' => new \DateTime('2025-03-01'),
                'montant_caution' => 970,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2025-03-03'),
                'preavis_recu_le' => null,
                'debut_du_preavis' => null,
                'date_EDL_sortie' => null,
                'date_de_sortie' => null,
                'montant_solde_de_tout_compte' => null,
                'date_solde_de_tout_compte' => null,
                'mode_paiement_solde_de_tout_compte' => null,
                'banque_solde_de_tout_compte' => null,
                'cloture_contrat_visale' => false,
                'a_quitte_le_logement' => false,
            ],
            
            #Locataire ayant quitté la COLOC ronsard_60_ch1
            [
                'nom' => 'MOREAU',
                'prenom' => 'Hugos',
                'tel' => '0655667788',
                'mail' => 'hugos.moreau@example.com',
                'date_de_naissance' => new \DateTime('1996-09-30'),
                'lieu_de_naissance' => 'Rouen',
                'Statut'=>'etudiant',
                'LogementsID' =>  $ronsard_60_ch1,
                'debut_bail' => new \DateTime('2023-06-01'),
                'montant_caution' => 880,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2023-06-03'),
                'preavis_recu_le' => new \DateTime('2024-11-05'),
                'debut_du_preavis' => new \DateTime('2024-12-01'),
                'date_EDL_sortie' => new \DateTime('2025-01-02'),
                'date_de_sortie' => new \DateTime('2025-01-02'),
                'montant_solde_de_tout_compte' => -52,
                'date_solde_de_tout_compte' => new \DateTime('2025-02-01'),
                'mode_paiement_solde_de_tout_compte' => 'ESP',
                'banque_solde_de_tout_compte' => 'Societe Generale',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],
            #Locataire ayant quitté la COLOC ronsard_60_ch2
            [
                'nom' => 'PICARD',
                'prenom' => 'Alexandre',
                'tel' => '0610101112',
                'mail' => 'alexandre.picard@example.com',
                'date_de_naissance' => new \DateTime('1993-12-07'),
                'lieu_de_naissance' => 'Le Mans',
                'Statut'=>'travailleur',
                'LogementsID' =>  $ronsard_60_ch2,
                'debut_bail' => new \DateTime('2021-09-01'),
                'montant_caution' => 880,
                # loyer_TCC
                'restant_du_trop_percu' => 0,
                'date_EDL_entree' => new \DateTime('2021-09-03'),
                'preavis_recu_le' => new \DateTime('2022-03-10'),
                'debut_du_preavis' => new \DateTime('2022-04-01'),
                'date_EDL_sortie' => new \DateTime('2022-04-15'),
                'date_de_sortie' => new \DateTime('2022-04-15'),
                'montant_solde_de_tout_compte' => -40,
                'date_solde_de_tout_compte' => new \DateTime('2022-05-05'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'Caisse d\'Epargne',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],

            #Locataire ayant quitté la COLOC ronsard_60_ch2
            [
                'nom' => 'LAMBERT',
                'prenom' => 'Sophie',
                'tel' => '0620202122',
                'mail' => 'sophie.lambert@example.com',
                'date_de_naissance' => new \DateTime('1994-03-18'),
                'lieu_de_naissance' => 'Metz',
                'Statut'=>'etudiant',
                'LogementsID' =>  $ronsard_60_ch2,
                'debut_bail' => new \DateTime('2022-05-01'),
                'montant_caution' => 900,
                # loyer_TCC
                'restant_du_trop_percu' => 10,
                'date_EDL_entree' => new \DateTime('2022-05-03'),
                'preavis_recu_le' => new \DateTime('2023-10-10'),
                'debut_du_preavis' => new \DateTime('2023-11-01'),
                'date_EDL_sortie' => new \DateTime('2023-11-30'),
                'date_de_sortie' => new \DateTime('2023-11-30'),
                'montant_solde_de_tout_compte' => 25,
                'date_solde_de_tout_compte' => new \DateTime('2023-12-20'),
                'mode_paiement_solde_de_tout_compte' => 'VIR',
                'banque_solde_de_tout_compte' => 'BNP',
                'cloture_contrat_visale' => true,
                'a_quitte_le_logement' => true,
            ],
        ];


        foreach ($locataires as $data) {
            $locataire = new Locataires();

            $locataire->setNom($data['nom']);
            $locataire->setPrenom($data['prenom']);
            $locataire->setTel($data['tel']);
            $locataire->setMail($data['mail']);
            $locataire->setDateDeNaissance($data['date_de_naissance']);
            $locataire->setLieuDeNaissance($data['lieu_de_naissance']);
            $locataire->setStatut($data['Statut']);

            $locataire->setDebutBail($data['debut_bail']);
            $locataire->setMontantCaution($data['montant_caution']);
            $locataire->setRestantDuTropPercu($data['restant_du_trop_percu']);
            $locataire->setDateEDLEntree($data['date_EDL_entree']);
            $locataire->setPreavisRecuLe($data['preavis_recu_le']);
            $locataire->setDebutDuPreavis($data['debut_du_preavis']);
            $locataire->setDateEDLSortie($data['date_EDL_sortie']);
            $locataire->setDateDeSortie($data['date_de_sortie']);
            $locataire->setMontantSoldeDeToutCompte($data['montant_solde_de_tout_compte']);
            $locataire->setDateSoldeDeToutCompte($data['date_solde_de_tout_compte']);
            $locataire->setModePaiementSoldeDeToutCompte($data['mode_paiement_solde_de_tout_compte']);
            $locataire->setBanqueSoldeDeToutCompte($data['banque_solde_de_tout_compte']);
            $locataire->setClotureContratVisale($data['cloture_contrat_visale']);
            $locataire->setAQuitteLeLogement($data['a_quitte_le_logement']);
            # $locataire->setGarants($data['Garants']);
            # $locataire->setPaiementsMensuels($data['Paiements_mensuels']);
            $locataire->setLogementsID($data['LogementsID']);

            $manager->persist($locataire);

            $key = 'locataire_' . strtolower(str_replace(' ', '_', $data['mail']));
            print($key . ',     '); ##locataire_marcel.buche-gatignol@example.com,     locataire_jean.martin@example.com,     locataire_camille.durand@example.com,     locataire_nina.leroy@example.com,     locataire_hugo.moreau@example.com,     locataire_sarah.garnier@example.com,     locataire_amina.petit@example.com,     locataire_lucas.rousseau@example.com,     locataire_chloe.bernard@example.com,     locataire_emma.legrand@example.com,     locataire_yanis.marchand@example.com,     locataire_noah.garcias@example.com,     locataire_ines.caron@example.com,     locataire_hugos.moreau@example.com,     locataire_alexandre.picard@example.com,     locataire_sophie.lambert@example.com, 
            $this->addReference($key, $locataire);
        }
        print("\n");

        $manager->flush();
    }
}
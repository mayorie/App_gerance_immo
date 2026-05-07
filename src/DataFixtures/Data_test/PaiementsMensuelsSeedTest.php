<?php

namespace App\DataFixtures\Data_test;

use App\Entity\PaiementsMensuels;
use App\Entity\Locataires;
use App\Entity\RBTBailleur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PaiementsMensuelsSeedTest extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['test'];
    }

    public function getDependencies(): array
    {
        return [
            LocatairesSeedTest::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        // Récupération de quelques locataires existants (références supposées)
        $BucheGatignol = $this->getReference('locataire_marcel.buche-gatignol@example.com', Locataires::class);
        $Martin = $this->getReference('locataire_jean.martin@example.com', Locataires::class);
        $Leroy = $this->getReference('locataire_nina.leroy@example.com', Locataires::class);
        $Moreau = $this->getReference('locataire_hugo.moreau@example.com', Locataires::class);
        $Garnier = $this->getReference('locataire_sarah.garnier@example.com', Locataires::class);
        $Petit = $this->getReference('locataire_amina.petit@example.com', Locataires::class);
        $Durand = $this->getReference('locataire_camille.durand@example.com', Locataires::class);
        $Rousseau = $this->getReference('locataire_lucas.rousseau@example.com', Locataires::class);
        $Bernard = $this->getReference('locataire_chloe.bernard@example.com', Locataires::class);
        $Legrand = $this->getReference('locataire_emma.legrand@example.com', Locataires::class);
        $Marchand = $this->getReference('locataire_yanis.marchand@example.com', Locataires::class);
        $Garcias = $this->getReference('locataire_noah.garcias@example.com', Locataires::class);
        $Caron = $this->getReference('locataire_ines.caron@example.com', Locataires::class);
        $hugos = $this->getReference('locataire_hugos.moreau@example.com', Locataires::class);
        $Picard = $this->getReference('locataire_alexandre.picard@example.com', Locataires::class);
        $Lambert = $this->getReference('locataire_sophie.lambert@example.com', Locataires::class);

        $datas = [
            [
                'locataire' => $Martin,
                'date' => new \DateTime('2024-03-01'),
                'locataire_paiement_date' => new \DateTime('2024-03-05'),
                'locataire_paiement_mode' => 'ESP',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-03-10'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Rousseau,
                'date' => new \DateTime('2025-03-01'),
                'locataire_paiement_date' => new \DateTime('2025-03-18'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 100,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Rousseau,
                'date' => new \DateTime('2025-03-01'),
                'locataire_paiement_date' => null,
                'locataire_paiement_mode' => null,
                'locataire_paiement_montant' => null,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => [
                    'motif' => 'Trop perçu',
                    'date' => new \DateTime('2025-04-15'),
                    'montant' => 100,
                    'mode' => 'VIR',
                ]
            ],
            [
                'locataire' => $Petit,
                'date' => new \DateTime('2024-03-01'),
                'locataire_paiement_date' => new \DateTime('2024-03-08'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-03-05'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Moreau,
                'date' => new \DateTime('2024-03-01'),
                'locataire_paiement_date' => new \DateTime('2024-03-09'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-03-05'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Martin,
                'date' => new \DateTime('2024-04-01'),
                'locataire_paiement_date' => new \DateTime('2024-04-05'),
                'locataire_paiement_mode' => 'ESP',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-04-10'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Petit,
                'date' => new \DateTime('2024-04-01'),
                'locataire_paiement_date' => new \DateTime('2024-04-08'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-04-05'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Moreau,
                'date' => new \DateTime('2024-04-01'),
                'locataire_paiement_date' => new \DateTime('2024-04-09'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-04-05'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Martin,
                'date' => new \DateTime('2024-05-01'),
                'locataire_paiement_date' => new \DateTime('2024-05-05'),
                'locataire_paiement_mode' => 'ESP',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-05-10'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Petit,
                'date' => new \DateTime('2024-05-01'),
                'locataire_paiement_date' => new \DateTime('2024-05-08'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-05-05'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $Moreau,
                'date' => new \DateTime('2024-05-01'),
                'locataire_paiement_date' => new \DateTime('2024-05-09'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 300,
                'caf_paiement_date' => new \DateTime('2024-05-05'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2024-09-01'),
                'locataire_paiement_date' => new \DateTime('2024-09-05'),
                'locataire_paiement_mode' => 'ESP',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => [
                    'motif' => 'Trop perçu',
                    'date' => new \DateTime('2024-09-15'),
                    'montant' => 100,
                    'mode' => 'CB',
                ]
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2024-10-01'),
                'locataire_paiement_date' => new \DateTime('2024-10-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2024-11-01'),
                'locataire_paiement_date' => new \DateTime('2024-11-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2024-12-01'),
                'locataire_paiement_date' => new \DateTime('2024-12-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-01-01'),
                'locataire_paiement_date' => new \DateTime('2025-01-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-02-01'),
                'locataire_paiement_date' => new \DateTime('2025-02-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-03-01'),
                'locataire_paiement_date' => new \DateTime('2025-03-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-04-01'),
                'locataire_paiement_date' => new \DateTime('2025-04-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-05-01'),
                'locataire_paiement_date' => new \DateTime('2025-05-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 450,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-06-01'),
                'locataire_paiement_date' => new \DateTime('2025-06-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 650,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-07-01'),
                'locataire_paiement_date' => new \DateTime('2025-06-29'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-08-01'),
                'locataire_paiement_date' => new \DateTime('2025-08-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-09-01'),
                'locataire_paiement_date' => new \DateTime('2025-09-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-09-01'),
                'locataire_paiement_date' => new \DateTime('2025-09-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => [
                    'motif' => 'Double paiement de loyer',
                    'date' => new \DateTime('2025-09-18'),
                    'montant' => 550,
                    'mode' => 'VIR',
                ]
            ],

            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-10-01'),
                'locataire_paiement_date' => new \DateTime('2025-10-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-11-01'),
                'locataire_paiement_date' => new \DateTime('2025-11-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2025-12-01'),
                'locataire_paiement_date' => new \DateTime('2025-12-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2026-01-01'),
                'locataire_paiement_date' => new \DateTime('2026-01-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2026-02-01'),
                'locataire_paiement_date' => new \DateTime('2026-02-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2026-03-01'),
                'locataire_paiement_date' => new \DateTime('2026-03-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2026-04-01'),
                'locataire_paiement_date' => new \DateTime('2026-04-05'),
                'locataire_paiement_mode' => 'VIR',
                'locataire_paiement_montant' => 550,
                'caf_paiement_date' => null,
                'caf_paiement_mode' => null,
                'caf_paiement_montant' => null,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ]
        ];


        foreach ($datas as $donnee) {
            $paiement = new PaiementsMensuels();

            $paiement->setLocatairesID($donnee['locataire']);
            $paiement->setDate($donnee['date']);

            $paiement->setPartRecueDuLocataireDate($donnee['locataire_paiement_date']);
            $paiement->setPartRecueDuLocataireMode($donnee['locataire_paiement_mode']);
            $paiement->setPartRecueDuLocataireMontant($donnee['locataire_paiement_montant']);

            $paiement->setPartRecueDeLaCAFDate($donnee['caf_paiement_date']);
            $paiement->setPartRecueDeLaCAFMode($donnee['caf_paiement_mode']);
            $paiement->setPartRecueDeLaCAFMontant($donnee['caf_paiement_montant']);

            $paiement->setRegulPacksServices($donnee['regul_pack']);
            $paiement->setRegulProvisionsPourCharges($donnee['regul_charges']);

            $paiement->setRestantDuTropPercuFinDeMois($donnee['restant_du']);

            if ($donnee['rbt']) {
                $rbt = new RBTBailleur();
                $rbt->setMotif($donnee['rbt']['motif']);
                $rbt->setDate($donnee['rbt']['date']);
                $rbt->setMontant($donnee['rbt']['montant']);
                $rbt->setMode($donnee['rbt']['mode']);

                // 🔗 liaison obligatoire
                $rbt->setPaiementsMensuelID($paiement);

                $manager->persist($rbt);
            }

            $manager->persist($paiement);
        }

        $manager->flush();
    }
}
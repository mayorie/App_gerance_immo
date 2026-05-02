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
                'locataire_paiement_montant' => 500,
                'caf_paiement_date' => new \DateTime('2024-03-10'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => null
            ],
            [
                'locataire' => $BucheGatignol,
                'date' => new \DateTime('2024-03-01'),
                'locataire_paiement_date' => new \DateTime('2024-03-05'),
                'locataire_paiement_mode' => 'ESP',
                'locataire_paiement_montant' => 500,
                'caf_paiement_date' => new \DateTime('2024-03-10'),
                'caf_paiement_mode' => 'VIR',
                'caf_paiement_montant' => 150,
                'regul_pack' => 0,
                'regul_charges' => 0,
                'restant_du' => 0,
                'rbt' => [
                    'motif' => 'Trop perçu',
                    'date' => new \DateTime('2024-03-15'),
                    'montant' => 50,
                    'mode' => 'VIR',
                ]
            ],
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
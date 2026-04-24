<?php

namespace App\DataFixtures\Data_test;

use App\Entity\ProvisionsPourCharges;
use App\Entity\Locataires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProvisionsPourChargesSeedTest extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
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

        $data = [
            [$BucheGatignol, 50.00, new \DateTime('2024-09-01')],
            [$BucheGatignol, 50.00, new \DateTime('2025-02-01')],
            [$Martin, 30.00, new \DateTime('2022-10-15')],
            [$Martin, 30.00, new \DateTime('2023-02-01')],
            [$Leroy, 45.00, new \DateTime('2025-01-10')],
            [$Leroy, 45.00, new \DateTime('2026-02-01')],
            [$Durand, 45.00, new \DateTime('2022-03-15')],
            [$Moreau, 43.00, new \DateTime('2023-06-01')],
            [$Garnier, 44.00, new \DateTime('2021-06-01')],
            [$Garnier, 45.00, new \DateTime('2022-02-01')],
            [$Garnier, 45.00, new \DateTime('2023-02-01')],
            [$Petit, 44.00, new \DateTime('2024-01-01')],
            [$Rousseau, 44.00, new \DateTime('2022-06-01')],
            [$Bernard, 45.00, new \DateTime('2021-01-15')],
            [$Legrand, 45.00, new \DateTime('2021-10-01')],
            [$Marchand, 45.00, new \DateTime('2022-10-10')],
            [$Garcias, 45.00, new \DateTime('2024-04-15')],
            [$Caron, 45.00, new \DateTime('2025-03-01')],
            [$hugos, 45.00, new \DateTime('2023-06-01')],
            [$Picard, 45.00, new \DateTime('2021-09-01')],
            [$Lambert, 45.00, new \DateTime('2022-05-01')],
            [$hugos, 50.00, new \DateTime('2022-05-05')],

        ];

        foreach ($data as [$locataire, $montant, $date]) {
            $PPC = new ProvisionsPourCharges();
            $PPC->setLocatairesID($locataire);
            $PPC->setMontant($montant);
            $PPC->setDateMES($date);

            $manager->persist($PPC);
        }

        $manager->flush();
    }
}
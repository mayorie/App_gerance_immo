<?php

namespace App\DataFixtures\Data_test;

use App\Entity\GarantsVisale;
use App\Entity\Garants;
use App\Entity\Locataires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GarantsVisaleSeedTest extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
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
        // $product = new Product();
        // $manager->persist($product);
        
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
            'texte' => 'Dupont',
            'date_anniversaire' => new \DateTime('1990-05-14'),
            'locataire' => $BucheGatignol,
            ],
            [
            'texte' => 'Dupont',
            'date_anniversaire' => new \DateTime('1990-05-14'),
            'locataire' => $BucheGatignol,
            ],
        ];

        foreach ($datas as $donnee) {

            // 🧱 1. créer GARANT
            $garant = new Garants();
            $garant->setType('VIS'); // 🔥 important
            $garant->setLocatairesID($donnee['locataire']);

            $manager->persist($garant);

            // 🧱 2. créer GARANT PHYSIQUE
            $GarantsVisale = new GarantsVisale();
            $GarantsVisale->setTexte($donnee['texte']);
            $GarantsVisale->setDateAnniversaire($donnee['date_anniversaire']);

            $GarantsVisale->setGarantsID($garant);
            $garant->setGarantsVisale($GarantsVisale);

            $manager->persist($GarantsVisale);
        }


        $manager->flush();
    }
}

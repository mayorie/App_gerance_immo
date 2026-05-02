<?php

namespace App\DataFixtures\Data_test;

use App\Entity\GarantsPhysiques;
use App\Entity\Garants;
use App\Entity\Locataires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GarantsPhysiquesSeedTest extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
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
            'locataire' => $Marchand,
            'nom' => 'Marchand',
            'prenom' => 'Yanis',
            'tel' => '123456',
            'mail' => 'mail.test@gmail.com',
            'adresse' => '1 rue JSP',
            'code_postal' => '31000',
            'ville' => 'Toulouse',
            'date_de_naissance' => new \DateTime('1990-05-14'),
            'lieu_de_naissance' => 'Toulouse',
            ],
            [
            'locataire' => $Marchand,
            'nom' => 'Marchand',
            'prenom' => 'Paul',
            'tel' => '0678904536',
            'mail' => 'paul.marchand@example.com',
            'adresse' => '1 rue JSP',
            'code_postal' => '31000',
            'ville' => 'Toulouse',
            'date_de_naissance' => new \DateTime('1990-05-14'),
            'lieu_de_naissance' => 'Toulouse',
            ],

            [
            'locataire' => $Garcias,
            'nom' => 'Garcias',
            'prenom' => 'Matteo',
            'tel' => '0612345678',
            'mail' => 'matteo.garcias@example.com',
            'adresse' => '12 avenue des Lilas',
            'code_postal' => '33000',
            'ville' => 'Bordeaux',
            'date_de_naissance' => new \DateTime('1993-11-02'),
            'lieu_de_naissance' => 'Bordeaux',
            ],
            [
            'locataire' => $Caron,
            'nom' => 'Caron',
            'prenom' => 'Lea',
            'tel' => '0623456789',
            'mail' => 'lea.caron@example.com',
            'adresse' => '7 impasse du Parc',
            'code_postal' => '34000',
            'ville' => 'Montpellier',
            'date_de_naissance' => new \DateTime('1998-03-27'),
            'lieu_de_naissance' => 'Montpellier',
            ],
            [
            'locataire' => $hugos,
            'nom' => 'Moreau',
            'prenom' => 'Nicolas',
            'tel' => '0634567890',
            'mail' => 'nicolas.moreau@example.com',
            'adresse' => '25 rue de la République',
            'code_postal' => '69000',
            'ville' => 'Lyon',
            'date_de_naissance' => new \DateTime('1991-07-19'),
            'lieu_de_naissance' => 'Lyon',
            ],
            [
            'locataire' => $Picard,
            'nom' => 'Picard',
            'prenom' => 'Lucas',
            'tel' => '0645678901',
            'mail' => 'lucas.picard@example.com',
            'adresse' => '3 boulevard Victor Hugo',
            'code_postal' => '59000',
            'ville' => 'Lille',
            'date_de_naissance' => new \DateTime('1989-01-08'),
            'lieu_de_naissance' => 'Lille',
            ],
            [
            'locataire' => $Picard,
            'nom' => 'Picard',
            'prenom' => 'Marie',
            'tel' => '0645678901',
            'mail' => 'marie.picard@example.com',
            'adresse' => '3 boulevard Victor Hugo',
            'code_postal' => '59000',
            'ville' => 'Lille',
            'date_de_naissance' => new \DateTime('1989-01-08'),
            'lieu_de_naissance' => 'Lille',
            ],
            [
            'locataire' => $Lambert,
            'nom' => 'Lambert',
            'prenom' => 'Camille',
            'tel' => '0656789012',
            'mail' => 'camille.lambert@example.com',
            'adresse' => '10 rue des Ecoles',
            'code_postal' => '44000',
            'ville' => 'Nantes',
            'date_de_naissance' => new \DateTime('1995-09-30'),
            'lieu_de_naissance' => 'Nantes',
            ],
        ];


        foreach ($datas as $donnee) {

            // 🧱 1. créer GARANT
            $garant = new Garants();
            $garant->setType('PHY'); // 🔥 important
            $garant->setLocatairesID($donnee['locataire']);

            $manager->persist($garant);

            // 🧱 2. créer GARANT PHYSIQUE
            $garantPhysique = new GarantsPhysiques();
            $garantPhysique->setNom($donnee['nom']);
            $garantPhysique->setPrenom($donnee['prenom']);
            $garantPhysique->setTel($donnee['tel']);
            $garantPhysique->setMail($donnee['mail']);
            $garantPhysique->setAdresse($donnee['adresse']);
            $garantPhysique->setCodePostal($donnee['code_postal']);
            $garantPhysique->setVille($donnee['ville']);
            $garantPhysique->setDateDeNaissance($donnee['date_de_naissance']);
            $garantPhysique->setLieuDeNaissance($donnee['lieu_de_naissance']);

            $garantPhysique->setGarantsID($garant);
            $garant->setGarantsPhysiques($garantPhysique);

            $manager->persist($garantPhysique);
        }


        $manager->flush();
    }
}

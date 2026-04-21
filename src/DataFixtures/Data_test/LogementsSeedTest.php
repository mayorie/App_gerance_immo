<?php

namespace App\DataFixtures\Data_test;

use App\Entity\Logements;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LogementsSeedTest extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $logements = [
            [
                'id_appart' => 'SOLEIL CH1',
                'residence' => 'Résidence Soleil',
                'batiment' => 'A',
                'appt' => '101',
                'adresse' => '10 rue de Paris',
                'code_postal' => 75001,
                'ville' => 'Paris',
                'SIRET' => '41488417100045',
                'num_chambre' => 1
            ],
            [
                'id_appart' => 'SOLEIL CH2',
                'residence' => 'Résidence Soleil',
                'batiment' => 'A',
                'appt' => '101',
                'adresse' => '10 rue de Paris',
                'code_postal' => 75001,
                'ville' => 'Paris',
                'SIRET' => '41488417100045',
                'num_chambre' => 2
            ],
            [
                'id_appart' => 'SOLEIL CH3',
                'residence' => 'Résidence Soleil',
                'batiment' => 'A',
                'appt' => '101',
                'adresse' => '10 rue de Paris',
                'code_postal' => 75001,
                'ville' => 'Paris',
                'SIRET' => '41488417100045',
                'num_chambre' => 3
            ],
            [
                'id_appart' => 'LUNE',
                'residence' => 'Résidence Lune',
                'batiment' => 'B',
                'appt' => '8',
                'adresse' => '25 avenue Perrache',
                'code_postal' => 69000,
                'ville' => 'Lyon',
                'SIRET' => '41488417100075',
                'num_chambre' => 1
            ],
            [
                'id_appart' => 'ETOILE',
                'residence' => 'Résidence Étoile',
                'batiment' => 'C',
                'appt' => '301',
                'adresse' => '5 boulevard Pagnol',
                'code_postal' => 13000,
                'ville' => 'Marseille',
                'SIRET' => '41488417100089',
                'num_chambre' => 1
            ],
            [
                'id_appart' => 'RONSARD 60 CH1',
                'residence' => 'Résidence Ronsard',
                'batiment' => 'E',
                'appt' => '60',
                'adresse' => '13 av. Paul Doumer',
                'code_postal' => 31100,
                'ville' => 'Toulouse',
                'SIRET' => '41488417100090',
                'num_chambre' => 1
            ],
            [
                'id_appart' => 'RONSARD 60 CH2',
                'residence' => 'Résidence Ronsard',
                'batiment' => 'E',
                'appt' => '60',
                'adresse' => '13 av. Paul Doumer',
                'code_postal' => 31100,
                'ville' => 'Toulouse',
                'SIRET' => '41488417100090',
                'num_chambre' => 2
            ],
            [
                'id_appart' => 'RONSARD 60 CH3',
                'residence' => 'Résidence Ronsard',
                'batiment' => 'E',
                'appt' => '60',
                'adresse' => '13 av. Paul Doumer',
                'code_postal' => 31100,
                'ville' => 'Toulouse',
                'SIRET' => '41488417100090',
                'num_chambre' => 3
            ],
            [
                'id_appart' => 'RONSARD 64 CH1',
                'residence' => 'Résidence Ronsard',
                'batiment' => 'E',
                'appt' => '64',
                'adresse' => '13 av. Paul Doumer',
                'code_postal' => 31100,
                'ville' => 'Toulouse',
                'SIRET' => '41488417100090',
                'num_chambre' => 1
            ],
            [
                'id_appart' => 'RONSARD 64 CH2',
                'residence' => 'Résidence Ronsard',
                'batiment' => 'E',
                'appt' => '64',
                'adresse' => '13 av. Paul Doumer',
                'code_postal' => 31100,
                'ville' => 'Toulouse',
                'SIRET' => '41488417100090',
                'num_chambre' => 2
            ],
            [
                'id_appart' => 'RONSARD 64 CH3',
                'residence' => 'Résidence Ronsard',
                'batiment' => 'E',
                'appt' => '64',
                'adresse' => '13 av. Paul Doumer',
                'code_postal' => 31100,
                'ville' => 'Toulouse',
                'SIRET' => '41488417100090',
                'num_chambre' => 3
            ],
        ];

        foreach ($logements as $data) {
            $logement = new Logements();

            $logement->setIdAppart($data['id_appart']);
            $logement->setResidence($data['residence']);
            $logement->setBatiment($data['batiment']);
            $logement->setAppt($data['appt']);
            $logement->setAdresse($data['adresse']);
            $logement->setCodePostal($data['code_postal']);
            $logement->setVille($data['ville']);
            $logement->setSIRET($data['SIRET']);
            $logement->setNumChambre($data['num_chambre']);

            $manager->persist($logement);

            #donne une référence à tout les appart : exemple : logement_RONSARD 64 CH3
            $key = 'logement_' . strtolower(str_replace(' ', '_', $data['id_appart']));
            print($key . ', '); #logement_soleil_ch1, logement_soleil_ch2, logement_soleil_ch3, logement_lune, logement_etoile, logement_ronsard_60_ch1, logement_ronsard_60_ch2, logement_ronsard_60_ch3, logement_ronsard_64_ch1, logement_ronsard_64_ch2, logement_ronsard_64_ch3, 
            $this->addReference($key, $logement);
        }
        print("\n");

        $manager->flush();
    }
}
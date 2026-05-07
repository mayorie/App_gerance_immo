<?php

namespace App\Controller;

use App\Repository\PaiementsMensuelsRepository;
use App\Repository\LocatairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class PaiementsMensuelsController extends AbstractController
{
    #[Route('/paiements', name: 'app_paiements_mensuels')]
    public function index(PaiementsMensuelsRepository $repoPaiements, LocatairesRepository $locataireRepo): Response {

        $paiements = $repoPaiements->findBy([], ['date' => 'DESC']);
        $firstPaiements = $repoPaiements->findFirstPaiementsIds();
        $locataires = $locataireRepo->findAll();

        // transformer en tableau simple [1, 5, 8, ...]
        $firstPaiementsIds = array_column($firstPaiements, 'id');

        $result = [];
        foreach ($paiements as $paiement) {

            $loyer = $repoPaiements->findLoyerHC($paiement);

            $result[] = [
                'paiement' => $paiement,
                'loyerHC'  => $loyer,
            ];
        }

        return $this->render('paiements_mensuels/index.html.twig', [
            'paiements' => $result,
            'firstPaiementsIds' => $firstPaiementsIds,
            'locataires' => $locataires,
            'locataire' => null,
            'mois' => null
        ]);
    }
}

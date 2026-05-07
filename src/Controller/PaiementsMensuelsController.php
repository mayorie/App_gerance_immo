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

        $paiements = $repoPaiements->findAllWithLoyerHC();
        $firstPaiements = $repoPaiements->findFirstPaiementsIds();
        $locataires = $locataireRepo->findAll();

        dd($paiements);
        
        // transformer en tableau simple [1, 5, 8, ...]
        $firstPaiementsIds = array_column($firstPaiements, 'id');

        return $this->render('paiements_mensuels/index.html.twig', [
            'paiements' => $paiements,
            'firstPaiementsIds' => $firstPaiementsIds,
            'locataires' => $locataires,
            'locataire' => null,
            'mois' => null
        ]);
    }
}

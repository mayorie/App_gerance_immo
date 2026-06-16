<?php

namespace App\Controller;

use App\Repository\LocatairesRepository;
use App\Repository\GarantsVisaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        LocatairesRepository $locatairesRepo,
        GarantsVisaleRepository $garantsVisaleRepo
    ): Response {
        $today = new \DateTime();
        $todayPlus3 = (clone $today)->modify('+3 days');

        $locatairesAlerte = [];

        $locataires = $locatairesRepo->findAll();

        foreach ($locataires as $locataire) {
            foreach ($locataire->getGarants() as $garant) {
                $garantsVisale = $garant->getGarantsVisale();
                if ($garantsVisale && $garantsVisale->getDateAnniversaire()) {
                    $dateAnniversaire = $garantsVisale->getDateAnniversaire();
                    
                    $anniversaireThisYear = (clone $dateAnniversaire)->setDate($today->format('Y'), $dateAnniversaire->format('m'), $dateAnniversaire->format('d'));
                    $anniversaireNextYear = (clone $dateAnniversaire)->setDate((int)$today->format('Y') + 1, $dateAnniversaire->format('m'), $dateAnniversaire->format('d'));

                    if ($anniversaireThisYear->format('m-d') === $todayPlus3->format('m-d') || 
                        $anniversaireNextYear->format('m-d') === $todayPlus3->format('m-d')) {
                        $locatairesAlerte[] = $locataire;
                    }
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'locatairesAlerte' => $locatairesAlerte,
        ]);
    }
}

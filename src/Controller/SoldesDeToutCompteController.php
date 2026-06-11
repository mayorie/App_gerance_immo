<?php

namespace App\Controller;

use App\Repository\LocatairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SoldesDeToutCompteController extends AbstractController
{
    #[Route('/soldes-de-tout-compte', name: 'app_soldes_de_tout_compte')]
    public function index(LocatairesRepository $locatairesRepo): Response
    {
        $locataires = $locatairesRepo->findAll();

        // Trier par date de solde de tout compte décroissante
        usort($locataires, function($a, $b) {
            $dateA = $a->getDateSoldeDeToutCompte();
            $dateB = $b->getDateSoldeDeToutCompte();

            if ($dateA === null && $dateB === null) {
                return 0;
            }
            if ($dateA === null) {
                return 1;
            }
            if ($dateB === null) {
                return -1;
            }

            return $dateB <=> $dateA;
        });

        return $this->render('soldes_de_tout_compte/index.html.twig', [
            'locataires' => $locataires,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\LocatairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SoldesDeToutCompteController extends AbstractController
{
    #[Route('/soldes-de-tout-compte', name: 'app_soldes_de_tout_compte')]
    public function index(Request $request, LocatairesRepository $locatairesRepo): Response
    {
        $anneeParam = $request->query->get('annee');
        $annee = $anneeParam !== null && $anneeParam !== '' ? (int) $anneeParam : null;
        $locataires = $locatairesRepo->findAll();

        // Filtrer par année si sélectionnée
        if ($annee !== null) {
            $locataires = array_filter($locataires, function($locataire) use ($annee) {
                $dateSolde = $locataire->getDateSoldeDeToutCompte();
                return $dateSolde && $dateSolde->format('Y') == $annee;
            });
        }

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

        // Récupérer les années disponibles
        $allLocataires = $locatairesRepo->findAll();
        $annees = [];
        foreach ($allLocataires as $loc) {
            $dateSolde = $loc->getDateSoldeDeToutCompte();
            if ($dateSolde) {
                $annees[$dateSolde->format('Y')] = true;
            }
        }
        krsort($annees);
        $anneesList = array_map(function($annee) {
            return ['annee' => $annee];
        }, array_keys($annees));

        return $this->render('soldes_de_tout_compte/index.html.twig', [
            'locataires' => $locataires,
            'annees' => $anneesList,
            'anneeSelectionnee' => $annee
        ]);
    }

    #[Route('/soldes-de-tout-compte/export-csv/{annee}', name: 'app_soldes_de_tout_compte_export_csv')]
    public function exportCsv(int $annee, LocatairesRepository $locatairesRepo): Response
    {
        $locataires = $locatairesRepo->findAll();

        // Filtrer par année
        $locataires = array_filter($locataires, function($locataire) use ($annee) {
            $dateSolde = $locataire->getDateSoldeDeToutCompte();
            return $dateSolde && $dateSolde->format('Y') == $annee;
        });

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="soldes_de_tout_compte_' . $annee . '.csv"');

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");

        // En-têtes
        fputcsv($handle, ['LOCATAIRE', 'LOGEMENT', 'NUM COMPTABLE', 'DATE SOLDE', 'MODE PAIEMENT', 'BANQUE', 'MONTANT SOLDE'], ";");

        foreach ($locataires as $locataire) {
            if ($locataire->getMontantSoldeDeToutCompte() !== null) {
                $dateSolde = $locataire->getDateSoldeDeToutCompte();

                $row = [
                    $locataire->getPrenom() . ' ' . $locataire->getNom(),
                    $locataire->getLogementsID() ? $locataire->getLogementsID()->getIdAppart() : '',
                    $locataire->getNumComptable() ?: '',
                    $dateSolde ? $dateSolde->format('d/m/Y') : '',
                    $locataire->getModePaiementSoldeDeToutCompte() ?: '',
                    $locataire->getBanqueSoldeDeToutCompte() ?: '',
                    number_format($locataire->getMontantSoldeDeToutCompte(), 2, ',', '')
                ];
                fputcsv($handle, $row, ";");
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Sauvegarder dans archive/comptabilité/[année]
        $directory = $this->getParameter('kernel.project_dir') . '/archive/comptabilité/' . $annee;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $filePath = $directory . '/soldes_de_tout_compte_' . $annee . '.csv';
        file_put_contents($filePath, $content);

        $response->setContent($content);
        return $response;
    }
}

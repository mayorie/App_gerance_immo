<?php

namespace App\Controller;

use App\Entity\NoteDeFrais;
use App\Entity\Pcg;
use App\Repository\NoteDeFraisRepository;
use App\Repository\PcgRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class NoteDeFraisController extends AbstractController
{
    #[Route('/notes-de-frais', name: 'note_de_frais_index')]
    public function index(Request $request, NoteDeFraisRepository $repo): Response
    {
        $anneeParam = $request->query->get('annee');
        $annee = $anneeParam !== null && $anneeParam !== '' ? (int) $anneeParam : null;
        $notesDeFrais = $repo->findByAnnee($annee);
        $annees = $repo->findAllAnnees();

        $totalDistance = 0.0;
        $totalPeage = 0.0;
        $totalParking = 0.0;
        $totalFrais = 0.0;
        $recapPcg = [];

        foreach ($notesDeFrais as $note) {
            $frais = (float) ($note->getFraisTotal() ?? 0);
            $totalDistance += (float) ($note->getDistance() ?? 0);
            $totalPeage += (float) ($note->getPeage() ?? 0);
            $totalParking += (float) ($note->getParking() ?? 0);
            $totalFrais += $frais;

            if ($note->getPcg()) {
                $pcgCompte = $note->getPcg()->getCompte();
                $pcgLibelle = $note->getPcg()->getLibelle();
                if (!isset($recapPcg[$pcgCompte])) {
                    $recapPcg[$pcgCompte] = [
                        'compte' => $pcgCompte,
                        'libelle' => $pcgLibelle,
                        'total' => 0.0,
                    ];
                }
                $recapPcg[$pcgCompte]['total'] += $frais;
            }
        }

        ksort($recapPcg);

        return $this->render('note_de_frais/index.html.twig', [
            'notesDeFrais' => $notesDeFrais,
            'annees' => $annees,
            'anneeSelectionnee' => $annee,
            'totalDistance' => $totalDistance,
            'totalPeage' => $totalPeage,
            'totalParking' => $totalParking,
            'totalFrais' => $totalFrais,
            'recapPcg' => $recapPcg,
        ]);
    }

    #[Route('/notes-de-frais/new', name: 'note_de_frais_new')]
    public function new(Request $request, EntityManagerInterface $em, PcgRepository $pcgRepo): Response
    {
        if ($request->isMethod('POST')) {
            $dates = $request->request->all('dates');
            $pcgIds = $request->request->all('pcg_ids');
            $motifs = $request->request->all('motifs');
            $distances = $request->request->all('distances');
            $peages = $request->request->all('peages');
            $parkings = $request->request->all('parkings');
            $fraisTotals = $request->request->all('frais_totals');

            $count = 0;

            // Mode saisie par lot (tableaux)
            if (!empty($dates) && is_array($dates)) {
                foreach ($dates as $index => $dateVal) {
                    $motifVal = trim($motifs[$index] ?? '');
                    
                    // On enregistre la ligne si au moins une date ou un motif est renseigné
                    if (!empty($dateVal) || !empty($motifVal)) {
                        $note = new NoteDeFrais();

                        if (!empty($dateVal)) {
                            $note->setDate(new \DateTimeImmutable($dateVal));
                        } else {
                            $note->setDate(new \DateTimeImmutable());
                        }

                        $pcgIdVal = $pcgIds[$index] ?? null;
                        if (!empty($pcgIdVal)) {
                            $pcg = $pcgRepo->find($pcgIdVal);
                            if ($pcg) {
                                $note->setPcg($pcg);
                            }
                        }

                        $dist = isset($distances[$index]) && $distances[$index] !== '' ? (float) $distances[$index] : null;
                        $peage = isset($peages[$index]) && $peages[$index] !== '' ? (float) $peages[$index] : null;
                        $parking = isset($parkings[$index]) && $parkings[$index] !== '' ? (float) $parkings[$index] : null;
                        
                        $fraisTot = isset($fraisTotals[$index]) && $fraisTotals[$index] !== '' 
                            ? (float) $fraisTotals[$index] 
                            : round((($dist ?? 0.0) * 0.234) + ($peage ?? 0.0) + ($parking ?? 0.0), 2);

                        $note->setMotif($motifVal);
                        $note->setDistance($dist);
                        $note->setPeage($peage);
                        $note->setParking($parking);
                        $note->setFraisTotal($fraisTot);

                        $em->persist($note);
                        $count++;
                    }
                }
            } else {
                // Fallback saisie unitaire (si formulaire classique)
                $date = $request->request->get('date');
                $pcgId = $request->request->get('pcg_id');
                $motif = $request->request->get('motif');
                $distance = $request->request->get('distance');
                $peage = $request->request->get('peage');
                $parking = $request->request->get('parking');
                $fraisTotal = $request->request->get('frais_total');

                if ($date || $motif) {
                    $note = new NoteDeFrais();
                    $note->setDate($date ? new \DateTimeImmutable($date) : new \DateTimeImmutable());

                    if ($pcgId) {
                        $pcg = $pcgRepo->find($pcgId);
                        if ($pcg) {
                            $note->setPcg($pcg);
                        }
                    }

                    $distVal = $distance !== null && $distance !== '' ? (float) $distance : null;
                    $peageVal = $peage !== null && $peage !== '' ? (float) $peage : null;
                    $parkingVal = $parking !== null && $parking !== '' ? (float) $parking : null;
                    $totalVal = ($fraisTotal !== null && $fraisTotal !== '') 
                        ? (float) $fraisTotal 
                        : round((($distVal ?? 0.0) * 0.234) + ($peageVal ?? 0.0) + ($parkingVal ?? 0.0), 2);

                    $note->setMotif($motif ?? '');
                    $note->setDistance($distVal);
                    $note->setPeage($peageVal);
                    $note->setParking($parkingVal);
                    $note->setFraisTotal($totalVal);

                    $em->persist($note);
                    $count++;
                }
            }

            if ($count > 0) {
                $em->flush();
                $this->addFlash('success', "$count note(s) de frais enregistrée(s) avec succès.");
            } else {
                $this->addFlash('warning', "Aucune ligne valide n'a été saisie.");
            }

            return $this->redirectToRoute('note_de_frais_index');
        }

        $pcgs = $pcgRepo->findByPrefix('625');

        return $this->render('note_de_frais/new.html.twig', [
            'pcgs' => $pcgs,
        ]);
    }

    #[Route('/notes-de-frais/edit/{id}', name: 'note_de_frais_edit')]
    public function edit(NoteDeFrais $note, Request $request, EntityManagerInterface $em, PcgRepository $pcgRepo): Response
    {
        if ($request->isMethod('POST')) {
            $date = $request->request->get('date');
            $pcgId = $request->request->get('pcg_id');
            $motif = $request->request->get('motif');
            $distance = $request->request->get('distance');
            $peage = $request->request->get('peage');
            $parking = $request->request->get('parking');
            $fraisTotal = $request->request->get('frais_total');

            if ($date) {
                $note->setDate(new \DateTimeImmutable($date));
            }

            if ($pcgId) {
                $pcg = $pcgRepo->find($pcgId);
                if ($pcg) {
                    $note->setPcg($pcg);
                }
            } else {
                $note->setPcg(null);
            }

            $distVal = $distance !== null && $distance !== '' ? (float) $distance : null;
            $peageVal = $peage !== null && $peage !== '' ? (float) $peage : null;
            $parkingVal = $parking !== null && $parking !== '' ? (float) $parking : null;
            $totalVal = ($fraisTotal !== null && $fraisTotal !== '') 
                ? (float) $fraisTotal 
                : round((($distVal ?? 0.0) * 0.234) + ($peageVal ?? 0.0) + ($parkingVal ?? 0.0), 2);

            $note->setMotif($motif);
            $note->setDistance($distVal);
            $note->setPeage($peageVal);
            $note->setParking($parkingVal);
            $note->setFraisTotal($totalVal);

            $em->flush();

            $this->addFlash('success', 'Note de frais modifiée avec succès.');

            return $this->redirectToRoute('note_de_frais_index');
        }

        $pcgs = $pcgRepo->findByPrefix('625');

        return $this->render('note_de_frais/edit.html.twig', [
            'note' => $note,
            'pcgs' => $pcgs,
        ]);
    }

    #[Route('/notes-de-frais/delete/{id}', name: 'note_de_frais_delete', methods: ['POST'])]
    public function delete(Request $request, NoteDeFrais $note, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('note_de_frais_index');
        }

        $em->remove($note);
        $em->flush();

        $this->addFlash('success', 'Note de frais supprimée avec succès.');

        return $this->redirectToRoute('note_de_frais_index');
    }

    #[Route('/notes-de-frais/export-csv/{annee}', name: 'note_de_frais_export_csv')]
    public function exportCsv(int $annee, NoteDeFraisRepository $repo, PcgRepository $pcgRepo): Response
    {
        $notes = $repo->findByAnnee($annee);

        // Regroupement par compte PCG 6 (comme dans le tableau récapitulatif)
        $recapPcg = [];
        foreach ($notes as $note) {
            $frais = (float) ($note->getFraisTotal() ?? 0);
            if ($note->getPcg()) {
                $compte = $note->getPcg()->getCompte();
                $libelle = $note->getPcg()->getLibelle();
                if (!isset($recapPcg[$compte])) {
                    $recapPcg[$compte] = [
                        'compte' => $compte,
                        'libelle' => $libelle,
                        'total' => 0.0,
                    ];
                }
                $recapPcg[$compte]['total'] += $frais;
            }
        }
        ksort($recapPcg);

        $pcg108 = $pcgRepo->findOneBy(['compte' => '108000']);
        $libellePcg108 = $pcg108 ? $pcg108->getLibelle() : "Compte de l'exploitant";

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_compta_notes_de_frais_' . $annee . '.csv"');

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");

        $dateFinAnnee = '31/12/' . $annee;

        foreach ($recapPcg as $item) {
            $montantStr = number_format($item['total'], 2, ',', '');

            // Ligne A
            // A1 = 31/12/[année], A2 = BQ, A3 = Compte PCG 6, A4 = Libellé PCG, A5 = Déplacements annuels, A6 = Frais total, A7 = ''
            $ligneA = [
                $dateFinAnnee,
                'BQ',
                $item['compte'],
                $item['libelle'],
                'Déplacements annuels',
                $montantStr,
                ''
            ];
            fputcsv($handle, $ligneA, ";");

            // Ligne B
            // B1 = 31/12/[année], B2 = BQ, B3 = 108000, B4 = Libellé 108000, B5 = PAIEMENT Déplacements annuels, B6 = '', B7 = Frais total
            $ligneB = [
                $dateFinAnnee,
                'BQ',
                '108000',
                $libellePcg108,
                'PAIEMENT Déplacements annuels',
                '',
                $montantStr
            ];
            fputcsv($handle, $ligneB, ";");
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Sauvegarder dans archive/comptabilité/[année]
        $directory = $this->getParameter('kernel.project_dir') . '/archive/comptabilité/' . $annee;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $filePath = $directory . '/export_compta_notes_de_frais_' . $annee . '.csv';
        file_put_contents($filePath, $content);

        $response->setContent($content);
        return $response;
    }
}

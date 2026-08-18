<?php

namespace App\Controller;

use App\Entity\FactureFourn;
use App\Entity\Pcg;
use App\Repository\FactureFournRepository;
use App\Repository\PcgRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class FactureFournController extends AbstractController
{
    #[Route('/facture-fourn', name: 'facture_fourn_index')]
    public function index(Request $request, FactureFournRepository $repo): Response
    {
        $anneeParam = $request->query->get('annee');
        $annee = $anneeParam !== null && $anneeParam !== '' ? (int) $anneeParam : null;
        $factureFourns = $repo->findByAnnee($annee);
        $annees = $repo->findAllAnnees();

        return $this->render('facture_fourn/index.html.twig', [
            'factureFourns' => $factureFourns,
            'annees' => $annees,
            'anneeSelectionnee' => $annee
        ]);
    }

    #[Route('/facture-fourn/new', name: 'facture_fourn_new')]
    public function new(Request $request, EntityManagerInterface $em, PcgRepository $pcgRepo): Response
    {
        if ($request->isMethod('POST')) {
            $dateFacture = $request->request->get('date_facture');
            $fournisseur = $request->request->get('fournisseur');
            $pcgId = $request->request->get('pcg_id');
            $pcg2Id = $request->request->get('pcg2_id');
            $motif = $request->request->get('motif');
            $montant = $request->request->get('montant');
            $montant2 = $request->request->get('montant2');
            $remise = $request->request->get('remise');
            $montantPaiement = $request->request->get('montant_paiement');
            $datePaiement = $request->request->get('date_paiement');
            $mode = $request->request->get('mode');

            $facture = new FactureFourn();
            $facture->setDateFacture(new \DateTimeImmutable($dateFacture));
            $facture->setFournisseur($fournisseur);
            
            $pcg = $pcgRepo->find($pcgId);
            if ($pcg) {
                $facture->setPcg($pcg);
            }
            
            if ($pcg2Id) {
                $pcg2 = $pcgRepo->find($pcg2Id);
                if ($pcg2) {
                    $facture->setPcg2($pcg2);
                }
            }
            
            $facture->setMotif($motif);
            $facture->setMontant((float) $montant);
            
            if ($remise) {
                $facture->setRemise((float) $remise);
            }
            
            if ($montant2) {
                $facture->setMontant2((float) $montant2);
            }
            
            if ($montantPaiement) {
                $facture->setMontantPaiement((float) $montantPaiement);
            }
            
            $facture->setDatePaiement(new \DateTimeImmutable($datePaiement));
            $facture->setMode($mode);

            $em->persist($facture);
            $em->flush();

            return $this->redirectToRoute('facture_fourn_index');
        }

        $pcgs = $pcgRepo->findAll();

        return $this->render('facture_fourn/new.html.twig', [
            'pcgs' => $pcgs
        ]);
    }

    #[Route('/facture-fourn/edit/{id}', name: 'facture_fourn_edit')]
    public function edit(FactureFourn $facture, Request $request, EntityManagerInterface $em, PcgRepository $pcgRepo): Response
    {
        if ($request->isMethod('POST')) {
            $dateFacture = $request->request->get('date_facture');
            $fournisseur = $request->request->get('fournisseur');
            $pcgId = $request->request->get('pcg_id');
            $pcg2Id = $request->request->get('pcg2_id');
            $motif = $request->request->get('motif');
            $montant = $request->request->get('montant');
            $montant2 = $request->request->get('montant2');
            $remise = $request->request->get('remise');
            $montantPaiement = $request->request->get('montant_paiement');
            $datePaiement = $request->request->get('date_paiement');
            $mode = $request->request->get('mode');

            if ($dateFacture) {
                $facture->setDateFacture(new \DateTimeImmutable($dateFacture));
            }

            if ($fournisseur) {
                $facture->setFournisseur($fournisseur);
            }

            if ($pcgId) {
                $pcg = $pcgRepo->find($pcgId);
                if ($pcg) {
                    $facture->setPcg($pcg);
                }
            }

            if ($pcg2Id) {
                $pcg2 = $pcgRepo->find($pcg2Id);
                if ($pcg2) {
                    $facture->setPcg2($pcg2);
                }
            } else {
                $facture->setPcg2(null);
            }

            if ($motif) {
                $facture->setMotif($motif);
            }

            if ($montant) {
                $facture->setMontant((float) $montant);
            }

            if ($remise !== null && $remise !== '') {
                $facture->setRemise((float) $remise);
            } else {
                $facture->setRemise(null);
            }

            if ($montant2 !== null && $montant2 !== '') {
                $facture->setMontant2((float) $montant2);
            } else {
                $facture->setMontant2(null);
            }

            if ($montantPaiement !== null && $montantPaiement !== '') {
                $facture->setMontantPaiement((float) $montantPaiement);
            } else {
                $facture->setMontantPaiement(null);
            }

            if ($datePaiement) {
                $facture->setDatePaiement(new \DateTimeImmutable($datePaiement));
            }

            if ($mode) {
                $facture->setMode($mode);
            }

            $em->flush();

            return $this->redirectToRoute('facture_fourn_index');
        }

        $pcgs = $pcgRepo->findAll();

        return $this->render('facture_fourn/edit.html.twig', [
            'facture' => $facture,
            'pcgs' => $pcgs
        ]);
    }

    #[Route('/facture-fourn/delete/{id}', name: 'facture_fourn_delete', methods: ['POST'])]
    public function delete(Request $request, FactureFourn $facture, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('facture_fourn_index');
        }

        $em->remove($facture);
        $em->flush();

        $this->addFlash('success', 'Facture supprimée avec succès.');

        return $this->redirectToRoute('facture_fourn_index');
    }

    #[Route('/facture-fourn/export-csv/{annee}', name: 'facture_fourn_export_csv')]
    public function exportCsv(int $annee, FactureFournRepository $repo): Response
    {
        $factures = $repo->findByAnnee($annee);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_factures_' . $annee . '.csv"');

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");

        foreach ($factures as $facture) {
            $dateFacture = $facture->getDateFacture()->format('d/m/Y');
            $datePaiement = $facture->getDatePaiement() ? $facture->getDatePaiement()->format('d/m/Y') : $dateFacture;
            $fournisseur = $facture->getFournisseur();
            $motif = $facture->getMotif();
            $montant1 = $facture->getMontant();
            $montant2 = $facture->getMontant2();
            $remise = $facture->getRemise();
            $montantPaiement = $facture->getMontantPaiement() ?? $montant1;
            $pcg1 = $facture->getPcg();
            $pcg2 = $facture->getPcg2();
            $comptePcg1 = $pcg1 ? $pcg1->getCompte() : '';
            $libellePcg1 = $pcg1 ? $pcg1->getLibelle() : '';
            $comptePcg2 = $pcg2 ? $pcg2->getCompte() : '';
            $libellePcg2 = $pcg2 ? $pcg2->getLibelle() : '';
            $mode = $facture->getMode();

            // Calcul du total (montant 1 + montant 2 si non vide - remise si non vide)
            $montantTotal = $montant1;
            if ($montant2 !== null) {
                $montantTotal += $montant2;
            }
            if ($remise !== null) {
                $montantTotal -= $remise;
            }

            // Ligne A (Achat - Compte PCG 1)
            $ligneA = [
                $dateFacture,
                'AC',
                $comptePcg1,
                $libellePcg1,
                $fournisseur . ' - ' . $motif,
                number_format($montant1, 2, ',', ''),
                ''
            ];
            fputcsv($handle, $ligneA, ";");

            // Ligne ABIS (Achat - Compte PCG 2) si non vide
            if ($pcg2 && $montant2 !== null) {
                $ligneABis = [
                    $dateFacture,
                    'AC',
                    $comptePcg2,
                    $libellePcg2,
                    $fournisseur . ' - ' . $motif,
                    number_format($montant2, 2, ',', ''),
                    ''
                ];
                fputcsv($handle, $ligneABis, ";");
            }

            // Ligne ATER (Remise) si non vide
            if ($remise !== null) {
                $ligneATer = [
                    $dateFacture,
                    'AC',
                    $comptePcg1,
                    $libellePcg1,
                    $fournisseur . ' - ' . $motif,
                    '',
                    number_format($remise, 2, ',', '')
                ];
                fputcsv($handle, $ligneATer, ";");
            }

            // Ligne B (Fournisseur - AC)
            $ligneB = [
                $dateFacture,
                'AC',
                '401000',
                'Fournisseurs',
                $fournisseur . ' - ' . $motif,
                '',
                number_format($montantTotal, 2, ',', '')
            ];
            fputcsv($handle, $ligneB, ";");

            // Ligne C (Fournisseur - BQ)
            $ligneC = [
                $datePaiement,
                'BQ',
                '401000',
                'Fournisseurs',
                $mode . ' - ' . $fournisseur . ' - ' . $motif,
                number_format($montantPaiement, 2, ',', ''),
                ''
            ];
            fputcsv($handle, $ligneC, ";");

            // Ligne D (Paiement)
            $compteBanque = ($mode === 'CB') ? '108000' : '512000';
            $libelleBanque = ($mode === 'CB') ? 'Compte de l' . "'" . 'exploitant' : 'Banques';
            $ligneD = [
                $datePaiement,
                'BQ',
                $compteBanque,
                $libelleBanque,
                $mode . ' - ' . $fournisseur . ' - ' . $motif,
                '',
                number_format($montantPaiement, 2, ',', '')
            ];
            fputcsv($handle, $ligneD, ";");

            // Ligne vide
            fputcsv($handle, [], ";");
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Sauvegarder dans archive/comptabilité/[année]
        $directory = $this->getParameter('kernel.project_dir') . '/archive/comptabilité/' . $annee;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $filePath = $directory . '/export_factures_' . $annee . '.csv';
        file_put_contents($filePath, $content);

        $response->setContent($content);
        return $response;
    }
}

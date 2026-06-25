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
            $motif = $request->request->get('motif');
            $montant = $request->request->get('montant');
            $datePaiement = $request->request->get('date_paiement');
            $mode = $request->request->get('mode');

            $facture = new FactureFourn();
            $facture->setDateFacture(new \DateTimeImmutable($dateFacture));
            $facture->setFournisseur($fournisseur);
            
            $pcg = $pcgRepo->find($pcgId);
            if ($pcg) {
                $facture->setPcg($pcg);
            }
            
            $facture->setMotif($motif);
            $facture->setMontant((float) $montant);
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
            $motif = $request->request->get('motif');
            $montant = $request->request->get('montant');
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

            if ($motif) {
                $facture->setMotif($motif);
            }

            if ($montant) {
                $facture->setMontant((float) $montant);
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
            $fournisseur = $facture->getFournisseur();
            $motif = $facture->getMotif();
            $montant = number_format($facture->getMontant(), 2, ',', '');
            $pcg = $facture->getPcg();
            $comptePcg = $pcg ? $pcg->getCompte() : '';
            $libellePcg = $pcg ? $pcg->getLibelle() : '';
            $mode = $facture->getMode();

            // Ligne A (Achat)
            $ligneA = [
                $dateFacture,
                'AC',
                $comptePcg,
                $libellePcg,
                $fournisseur . ' - ' . $motif,
                $montant,
                ''
            ];
            fputcsv($handle, $ligneA, ";");

            // Ligne B (Paiement)
            $compteBanque = ($mode === 'CB') ? '108000' : '512000';
            $libelleBanque = ($mode === 'CB') ? 'Compte de l' . "'" . 'exploitant' : 'Banques';
            $ligneB = [
                $dateFacture,
                'BQ',
                $compteBanque,
                $libelleBanque,
                $mode . ' - ' . $fournisseur . ' - ' . $motif,
                '',
                $montant
            ];
            fputcsv($handle, $ligneB, ";");

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

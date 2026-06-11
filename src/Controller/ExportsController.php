<?php

namespace App\Controller;

use App\Entity\Locataires;

use App\Repository\LocatairesRepository;
use App\Repository\PaiementsMensuelsRepository;
use App\Repository\RBTBailleurRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

use ZipArchive;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class ExportsController extends AbstractController
{
    #[Route('/exports', name: 'app_exports')]
    public function index(): Response
    {
        return $this->render('exports/index.html.twig', [
            'controller_name' => 'ExportsController',
        ]);
    }

    #[Route('/export/csv', name: 'export_csv')]
    public function exportCsv(): Response
    {
        $response = new Response();

        $response->headers->set(
            'Content-Type',
            'text/csv; charset=UTF-8'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="paiements.csv"'
        );

        $handle = fopen('php://temp', 'r+');

        // BOM UTF-8 pour Excel / Windows
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'Locataire',
            'Montant',
            'Date'
        ], "\t");

        fputcsv($handle, [
            'Dépont',
            500,
            '2026-05-01'
        ], "\t");

        rewind($handle);

        $content = stream_get_contents($handle);

        fclose($handle);

        $response->setContent($content);

        return $response;
    }

    #[Route('/exports/pdf/batch', name: 'export_pdf_batch')]
    public function exportPdfBatch(
        Request $request,
        LocatairesRepository $locatairesRepo
    ): Response
    {
        $mois = $request->query->getInt(
            'mois',
            (int) date('m')
        );

        $annee = $request->query->getInt(
            'annee',
            (int) date('Y')
        );

        $locataires = $locatairesRepo
            ->findAyantPaiementMoisEtAnnee(
                $mois,
                $annee
            );

        return $this->render(
            'exports/pdf_batch.html.twig',
            [
                'locataires' => $locataires,
                'mois' => $mois,
                'annee' => $annee
            ]
        );
    }

    #[Route('/exports/pdf/{id}/preview', name: 'export_pdf_preview')]
    public function previewPdf(
        int $id,
        Request $request,
        LocatairesRepository $repo,
        PaiementsMensuelsRepository $paiementsRepo,
        RBTBailleurRepository $rbtBailleurRepo,
    ): Response
    {
        $mois = $request->query->getInt('mois');
        $annee = $request->query->getInt('annee');

        $locataire = $repo->find($id);

        if (!$locataire) {
            throw $this->createNotFoundException('Locataire introuvable');
        }

        return $this->render(
            'exports/quittance.html.twig',
            $this->getQuittanceData(
                $locataire,
                $mois,
                $annee,
                $paiementsRepo,
                $rbtBailleurRepo
            )
        );
    }
    
    #[Route('/exports/pdf/{id}', name: 'export_pdf')]
    public function exportPdf(
        int $id,
        Request $request,
        LocatairesRepository $repo,
        PaiementsMensuelsRepository $paiementsRepo,
        RBTBailleurRepository $rbtBailleurRepo,
    ): Response
    {
        $mois = $request->query->getInt('mois');
        $annee = $request->query->getInt('annee');

        $locataire = $repo->find($id);

        if (!$locataire) {
            throw $this->createNotFoundException(
                'Locataire introuvable'
            );
        }

        $html = $this->renderView(
            'exports/quittance.html.twig',
            $this->getQuittanceData(
                $locataire,
                $mois,
                $annee,
                $paiementsRepo,
                $rbtBailleurRepo
            )
        );

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());

        $response->headers->set(
            'Content-Type',
            'application/pdf'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="quittance.pdf"'
        );

        return $response;
    }



    #[Route('/exports/pdf/batch/zip', name: 'export_pdf_batch_zip')]
    public function exportPdfBatchZip(
        Request $request,
        LocatairesRepository $locatairesRepo,
        PaiementsMensuelsRepository $paiementsRepo,
        RBTBailleurRepository $rbtRepo
    ): Response
    {
        $mois = $request->query->getInt('mois');
        $annee = $request->query->getInt('annee');

        $locataires = $locatairesRepo->findAyantPaiementMoisEtAnnee($mois, $annee);

        $zip = new \ZipArchive();

        // 👉 dossier cible dans ton projet
        $directory = $this->getParameter('kernel.project_dir') . '/archive/quittances';

        // 👉 sécurité : créer le dossier s'il n'existe pas
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // 👉 nom du fichier ZIP dans ton dossier
        $zipPath = $directory . "/" . $annee . '_' . $mois . '_quittances' . '.zip';

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Impossible de créer le ZIP');
        }

        foreach ($locataires as $locataire) {

            $data = $this->getQuittanceData(
                $locataire,
                $mois,
                $annee,
                $paiementsRepo,
                $rbtRepo
            );

            $html = $this->renderView('exports/quittance.html.twig', $data);

            $options = new \Dompdf\Options();
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfContent = $dompdf->output();

            $filename = $locataire->getNom() . '_' . $locataire->getPrenom() . '_' . $annee . '_' . $mois . '.pdf';

            $zip->addFromString($filename, $pdfContent);
        }

        $zip->close();

        return new \Symfony\Component\HttpFoundation\BinaryFileResponse($zipPath);
    }


    
    private function getQuittanceData(
        Locataires $locataire,
        int $mois,
        int $annee,
        PaiementsMensuelsRepository $paiementsRepo,
        RBTBailleurRepository $rbtBailleurRepo
    ): array
    {
        $paiements = $paiementsRepo->findByLocataireMoisEtAnnee(
            $locataire->getId(),
            $mois,
            $annee
        );

        $paiementsAvecRbt = [];

        foreach ($paiements as $paiement) {

            $rbt = $rbtBailleurRepo->findOneBy([
                'Paiements_mensuelID' => $paiement
            ]);

            $paiementsAvecRbt[] = [
                'paiement' => $paiement,
                'rbt' => $rbt
            ];
        }

        $loyer = null;
        $charges = null;
        $packServices = null;

        if (!empty($paiements)) {
            $paiementReference = $paiements[0];

            $loyer = $paiementsRepo->findLoyerHC($paiementReference);
            $charges = $paiementsRepo->findProvisionPourCharges($paiementReference);
            $packServices = $paiementsRepo->findPackService($paiementReference);
        }

        $lastPaiementPreviousMonth =
            $paiementsRepo->findLastPaiementPreviousMonth(
                $locataire->getId(),
                $mois,
                $annee
            );

        $caution = null;

        if ($lastPaiementPreviousMonth === null) {
            $caution = $locataire->getMontantCaution();
        }

        $regulPS = 0;
        $regulCharges = 0;

        foreach ($paiements as $paiement) {
            $regulPS += $paiement->getRegulPacksServices() ?? 0;
            $regulCharges += $paiement->getRegulProvisionsPourCharges() ?? 0;
        }

        $path = $this->getParameter('kernel.project_dir') . '/private/Signature.jpg';

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        $signature = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return [
            'locataire' => $locataire,
            'paiements' => $paiementsAvecRbt,
            'loyer' => $loyer,
            'charges' => $charges,
            'packServices' => $packServices,
            'mois' => $mois,
            'annee' => $annee,
            'lastPaiementPreviousMonth' => $lastPaiementPreviousMonth,
            'caution' => $caution,
            'regulPS' => $regulPS,
            'regulCharges' => $regulCharges,
            'signature' => $signature
        ];
    }
}

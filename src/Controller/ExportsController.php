<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

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

    #[Route('/export/pdf', name: 'export_pdf')]
    public function exportPdf(): Response
    {
        $html = '
            <h1>Paiements</h1>

            <table
                border="1"
                cellpadding="8"
                cellspacing="0"
                width="100%"
            >
                <thead>
                    <tr>
                        <th>Locataire</th>
                        <th>Montant</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Dépont</td>
                        <td>500 €</td>
                        <td>2026-05-01</td>
                    </tr>

                    <tr>
                        <td>Durand</td>
                        <td>650 €</td>
                        <td>2026-05-02</td>
                    </tr>
                </tbody>
            </table>
        ';

        $options = new Options();

        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $response = new Response(
            $dompdf->output()
        );

        $response->headers->set(
            'Content-Type',
            'application/pdf'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="paiements.pdf"'
        );

        return $response;
    }
}

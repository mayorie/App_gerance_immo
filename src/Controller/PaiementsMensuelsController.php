<?php

namespace App\Controller;

use App\Entity\Locataires;
use App\Entity\PaiementsMensuels;
use App\Entity\RBTBailleur;
use App\Repository\LocatairesRepository;
use App\Repository\PaiementsMensuelsRepository;
use App\Repository\PcgRepository;
use App\Repository\RBTBailleurRepository;
use App\Form\PaiementMensuelType;
use App\Form\PaiementsBatchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class PaiementsMensuelsController extends AbstractController
{
    #[Route('/paiements', name: 'app_paiements_mensuels')]
    public function index(
        Request $request,
        PaiementsMensuelsRepository $repoPaiements,
        LocatairesRepository $locataireRepo,
        RBTBailleurRepository $repoRBT
    ): Response {

        $locataireId = $request->query->get('locataireId');
        $annee = $request->query->get('annee');
        $page = $request->query->getInt('page', 1);
        $limit = 12;

        // Récupérer tous les paiements pour extraire les années
        $allPaiements = $repoPaiements->findBy([], ['date' => 'DESC']);
        $annees = [];
        foreach ($allPaiements as $paiement) {
            if ($paiement->getDate()) {
                $year = $paiement->getDate()->format('Y');
                if (!in_array($year, $annees)) {
                    $annees[] = $year;
                }
            }
        }
        rsort($annees);

        // Construire les critères de recherche
        $criteria = [];
        if ($locataireId) {
            $criteria['LocatairesID'] = $locataireId;
        }

        $paiements = $repoPaiements->findBy(
            $criteria,
            ['date' => 'DESC']
        );

        // Filtrer par année si sélectionnée
        if ($annee) {
            $paiements = array_filter($paiements, function($paiement) use ($annee) {
                return $paiement->getDate() && $paiement->getDate()->format('Y') == $annee;
            });
        }

        $firstPaiements = $repoPaiements->findFirstPaiementsIds();
        $locataires = $locataireRepo->findAll();

        $firstPaiementsIds = array_column($firstPaiements, 'id');

        $result = [];

        foreach ($paiements as $paiement) {

            $loyer = $repoPaiements->findLoyerHC($paiement);
            $charge = $repoPaiements->findProvisionPourCharges($paiement);
            $PS = $repoPaiements->findPackService($paiement);
            $RBT = $repoRBT->findByPaiement($paiement);

            $result[] = [
                'paiement' => $paiement,
                'loyerHC'  => $loyer,
                'charge'   => $charge,
                'PS'       => $PS,
                'RBT'      => $RBT,
            ];
        }

        // Pagination du résultat final
        $totalItems = count($result);
        $totalPages = ceil($totalItems / $limit);
        $offset = ($page - 1) * $limit;
        $paginatedResult = array_slice($result, $offset, $limit);

        return $this->render('paiements_mensuels/index.html.twig', [
            'paiements' => $paginatedResult,
            'allPaiements' => $result,
            'firstPaiementsIds' => $firstPaiementsIds,
            'locataires' => $locataires,
            'locataireId' => $locataireId,
            'annees' => $annees,
            'annee' => $annee,
            'mois' => null,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    #[Route('/paiements/batch', name: 'paiements_batch')]
    public function batch(
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $data = [
            'paiements' => []
        ];

        $form = $this->createForm(PaiementsBatchType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->get('paiements') as $paiementForm) {

                $paiement = $paiementForm->getData();

                $em->persist($paiement);

                $locataire = $paiement->getLocatairesID();

                $locataire->setRestantDuTropPercu(
                    $paiement->getRestantDuTropPercuFinDeMois()
                );

                $em->persist($locataire);

                $RBTMontant = $paiementForm->get('RBT_montant')->getData();

                if ($RBTMontant !== null) {

                    $rbt = new RBTBailleur();

                    $rbt->setMotif(
                        $paiementForm->get('RBT_motif')->getData()
                    );

                    $rbt->setDate(
                        $paiementForm->get('RBT_date')->getData()
                    );

                    $rbt->setMode(
                        $paiementForm->get('RBT_mode')->getData()
                    );

                    $rbt->setMontant($RBTMontant);

                    $rbt->setPaiementsMensuelID($paiement);

                    $em->persist($rbt);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_paiements_mensuels');
        }

        return $this->render('paiements_mensuels/batch.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/paiements/{id}/edit', name: 'paiement_edit')]
    public function edit(
        Request $request,
        PaiementsMensuels $paiement,
        EntityManagerInterface $em,
        RBTBailleurRepository $repoRBT
    ): Response {

        $rbt = $repoRBT->findOneBy([
            'Paiements_mensuelID' => $paiement
        ]);

        $form = $this->createForm(
            PaiementMensuelType::class,
            $paiement
        );

        // préchargement des champs RBT
        if ($rbt) {

            $form->get('RBT_motif')->setData(
                $rbt->getMotif()
            );

            $form->get('RBT_date')->setData(
                $rbt->getDate()
            );

            $form->get('RBT_mode')->setData(
                $rbt->getMode()
            );

            $form->get('RBT_montant')->setData(
                $rbt->getMontant()
            );

        } else {

            $form->get('RBT_motif')->setData(null);
            $form->get('RBT_date')->setData(null);
            $form->get('RBT_mode')->setData(null);
            $form->get('RBT_montant')->setData(null);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $locataire = $paiement->getLocatairesID();

            if ($locataire) {

                $locataire->setRestantDuTropPercu(
                    $paiement->getRestantDuTropPercuFinDeMois()
                );
            }

            // gestion du RBT
            $RBTMontant = $form->get('RBT_montant')->getData();

            if ($RBTMontant !== null) {

                if (!$rbt) {
                    $rbt = new RBTBailleur();
                    $rbt->setPaiementsMensuelID($paiement);
                }

                $rbt->setMotif(
                    $form->get('RBT_motif')->getData()
                );

                $rbt->setDate(
                    $form->get('RBT_date')->getData()
                );

                $rbt->setMode(
                    $form->get('RBT_mode')->getData()
                );

                $rbt->setMontant($RBTMontant);

                $em->persist($rbt);

            } elseif ($rbt) {

                // suppression si montant vidé
                $em->remove($rbt);
            }

            $em->flush();

            $this->addFlash(
                'success',
                'Paiement modifié.'
            );

            return $this->redirectToRoute(
                'app_paiements_mensuels'
            );
        }

        return $this->render(
            'paiements_mensuels/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/paiements/{id}/delete', name: 'paiements_delete', methods: ['POST'])]
    public function delete(
        PaiementsMensuels $paiement,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $submittedToken = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('delete' . $paiement->getId(), $submittedToken)) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('app_paiements_mensuels');
        }

        $locataire = $paiement->getLocatairesID();

        $em->remove($paiement);
        $em->flush();

        $lastPaiement = $locataire->getLatestPaiement();
        if ($lastPaiement) {

            $locataire->setRestantDuTropPercu(
                $lastPaiement->getRestantDuTropPercuFinDeMois()
            );
        } else {
            $locataire->setRestantDuTropPercu(0);
        }

        $em->flush();
        
        $this->addFlash('success', 'Paiement supprimé');

        return $this->redirectToRoute('app_paiements_mensuels');
    }

    #[Route('/paiements/export-csv', name: 'paiements_export_csv')]
    public function exportCsv(
        Request $request,
        PaiementsMensuelsRepository $repoPaiements,
        LocatairesRepository $locataireRepo,
        RBTBailleurRepository $repoRBT
    ): Response {
        $locataireId = $request->query->get('locataireId');
        $annee = $request->query->get('annee');
        $comptabilite = $request->query->getBoolean('comptabilite', false);

        // Construire les critères de recherche
        $criteria = [];
        if ($locataireId) {
            $criteria['LocatairesID'] = $locataireId;
        }

        $paiements = $repoPaiements->findBy($criteria, ['date' => 'DESC']);

        // Filtrer par année si sélectionnée
        if ($annee) {
            $paiements = array_filter($paiements, function($paiement) use ($annee) {
                return $paiement->getDate() && $paiement->getDate()->format('Y') == $annee;
            });
        }

        $firstPaiements = $repoPaiements->findFirstPaiementsIds();
        $firstPaiementsIds = array_column($firstPaiements, 'id');

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $filename = 'paiements_' . ($annee ?: 'all') . '.csv';
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");

        // En-têtes
        $headers = [
            'LOCATAIRE',
            'LOGEMENT',
            'NUM COMPTABLE',
            'SAISIE DU',
            'CAUTION',
            'REGUL. PACK S',
            'REGUL. CHARGES',
            'LOYER HC',
            'CHARGES',
            'PACK S',
            'DATE LOC',
            'MODE LOC',
            'MONTANT LOC',
            'DATE CAF',
            'MODE CAF',
            'MONTANT CAF',
            'DATE RBT',
            'MODE RBT',
            'MONTANT RBT',
            'RESTANT DU'
        ];
        fputcsv($handle, $headers, ";");

        foreach ($paiements as $paiement) {
            $loyer = $repoPaiements->findLoyerHC($paiement);
            $charge = $repoPaiements->findProvisionPourCharges($paiement);
            $PS = $repoPaiements->findPackService($paiement);
            $RBT = $repoRBT->findByPaiement($paiement);
            $locataire = $paiement->getLocatairesID();

            $row = [
                $locataire ? $locataire->getPrenom() . ' ' . $locataire->getNom() : '',
                $locataire && $locataire->getLogementsID() ? $locataire->getLogementsID()->getIdAppart() : '',
                $locataire ? $locataire->getNumComptable() : '',
                $paiement->getDate() ? $paiement->getDate()->format('d/m/Y') : '',
                in_array($paiement->getId(), $firstPaiementsIds) && $locataire ? $locataire->getMontantCaution() : 0,
                $paiement->getRegulPacksServices() ?? 0,
                $paiement->getRegulProvisionsPourCharges() ?? 0,
                $loyer ?? 0,
                $charge ?? 0,
                $PS ?? 0,
                $paiement->getPartRecueDuLocataireDate() ? $paiement->getPartRecueDuLocataireDate()->format('d/m/Y') : '',
                $paiement->getPartRecueDuLocataireMode() ?: '',
                $paiement->getPartRecueDuLocataireMontant() ?? 0,
                $paiement->getPartRecueDeLaCafDate() ? $paiement->getPartRecueDeLaCafDate()->format('d/m/Y') : '',
                $paiement->getPartRecueDeLaCafMode() ?: '',
                $paiement->getPartRecueDeLaCafMontant() ?? 0,
                $RBT && $RBT->getDate() ? $RBT->getDate()->format('d/m/Y') : '',
                $RBT ? $RBT->getMode() : '',
                $RBT && $RBT->getMontant() !== null ? $RBT->getMontant() : 0,
                $paiement->getRestantDuTropPercuFinDeMois() ?? 0
            ];

            // Formatter les montants avec virgule
            $row = array_map(function($val) {
                if (is_numeric($val)) {
                    return number_format($val, 2, ',', '');
                }
                return $val;
            }, $row);

            fputcsv($handle, $row, ";");
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Sauvegarder dans archive/comptabilité/[année] si comptabilité est coché et année sélectionnée
        if ($comptabilite && $annee) {
            $directory = $this->getParameter('kernel.project_dir') . '/archive/comptabilité/' . $annee;
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            $filePath = $directory . '/paiements_' . $annee . '.csv';
            file_put_contents($filePath, $content);
        }

        $response->setContent($content);
        return $response;
    }

    #[Route('/paiements/export-compta', name: 'paiements_export_compta')]
    public function exportCompta(
        Request $request,
        PaiementsMensuelsRepository $repoPaiements,
        PcgRepository $pcgRepo
    ): Response {
        $locataireId = $request->query->get('locataireId');
        $annee = $request->query->get('annee');

        // Construire les critères de recherche
        $criteria = [];
        if ($locataireId) {
            $criteria['LocatairesID'] = $locataireId;
        }

        $paiements = $repoPaiements->findBy($criteria, ['date' => 'DESC']);

        // Filtrer par année si sélectionnée
        if ($annee) {
            $paiements = array_filter($paiements, function($paiement) use ($annee) {
                return $paiement->getDate() && $paiement->getDate()->format('Y') == $annee;
            });
        }

        $pcg708 = $pcgRepo->findOneBy(['compte' => '708000']);
        $libellePcg708 = $pcg708 ? $pcg708->getLibelle() : '';

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $filename = 'export_compta_' . ($annee ?: 'all') . '.csv';
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF");

        foreach ($paiements as $paiement) {
            $date = $paiement->getDate();
            $dateStr = $date ? $date->format('d/m/Y') : '';
            $journal = 'VE';

            $locataire = $paiement->getLocatairesID();
            $logement = $locataire ? $locataire->getLogementsID() : null;
            $pcgPrestation = $logement ? $logement->getPcgPrestation() : null;
            $pcgCompte = $pcgPrestation ? $pcgPrestation->getCompte() : '';
            $pcgLibelle = $pcgPrestation ? $pcgPrestation->getLibelle() : '';

            $nomLocataire = $locataire ? $locataire->getNom() : '';
            $moisStr = $date ? $date->format('m') : '';
            $anneeStr = $date ? $date->format('Y') : '';

            // A5: Concatener « LOYER » avec [Locataires.Nom] avec [saisie du.date.Mois] avec [saisie du.date.Année] avec « - LOYER HC »
            $libelleA = 'LOYER ' . $nomLocataire . ' ' . $moisStr . ' ' . $anneeStr . ' - LOYER HC';

            // A6: [Loyer HC]
            $loyerHC = $repoPaiements->findLoyerHC($paiement);
            if ($locataire && $date && $loyerHC !== null) {
                $loyerHC = $this->calculerProrataMontant($locataire, (int)$date->format('m'), (int)$date->format('Y'), $loyerHC);
            }
            $montantLoyer = $loyerHC !== null ? number_format($loyerHC, 2, ',', '') : '0,00';

            // Ligne A
            $ligneA = [
                $dateStr,
                $journal,
                $pcgCompte,
                $pcgLibelle,
                $libelleA,
                $montantLoyer
            ];
            fputcsv($handle, $ligneA, ";");

            // B5: A5 en remplaçant « - LOYER HC » par «- Prov. Charges »
            $libelleB = str_replace('- LOYER HC', '- Prov. Charges', $libelleA);

            // B6: [Charges]
            $charges = $repoPaiements->findProvisionPourCharges($paiement);
            if ($locataire && $date && $charges !== null) {
                $charges = $this->calculerProrataMontant($locataire, (int)$date->format('m'), (int)$date->format('Y'), $charges);
            }
            $montantCharges = $charges !== null ? number_format($charges, 2, ',', '') : '0,00';

            // Ligne B
            $ligneB = [
                $dateStr,
                $journal,
                '708000',
                $libellePcg708,
                $libelleB,
                $montantCharges
            ];
            fputcsv($handle, $ligneB, ";");

            // Ligne vide
            fputcsv($handle, [], ";");
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Sauvegarder dans archive/comptabilité/[année] si année spécifiée
        if ($annee) {
            $directory = $this->getParameter('kernel.project_dir') . '/archive/comptabilité/' . $annee;
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            $filePath = $directory . '/export_compta_' . $annee . '.csv';
            file_put_contents($filePath, $content);
        }

        $response->setContent($content);
        return $response;
    }

    private function calculerProrataMontant(
        Locataires $locataire,
        int $mois,
        int $annee,
        ?float $montant
    ): ?float {
        if ($montant === null) {
            return null;
        }

        $debutBail = $locataire->getDebutBail();
        $dateSortie = $locataire->getDateDeSortie();

        // Nombre de jours dans le mois
        $dateDebutMois = new \DateTime(sprintf('%04d-%02d-01', $annee, $mois));
        $dateFinMois = (clone $dateDebutMois)->modify('last day of this month');
        $joursDansLeMois = (int)$dateFinMois->format('d');

        // Prorata entrée (premier mois de bail)
        if ($debutBail && $debutBail->format('Y-m') === sprintf('%04d-%02d', $annee, $mois)) {
            $jourEntree = (int)$debutBail->format('d');
            $joursAPayer = $joursDansLeMois - $jourEntree + 1;
            $montant = round(($montant / $joursDansLeMois) * $joursAPayer, 2);
        }

        // Prorata sortie (dernier mois de bail)
        if ($dateSortie && $dateSortie->format('Y-m') === sprintf('%04d-%02d', $annee, $mois)) {
            $jourSortie = (int)$dateSortie->format('d');
            $joursAPayer = $jourSortie;
            $montant = round(($montant / $joursDansLeMois) * $joursAPayer, 2);
        }

        return $montant;
    }
}

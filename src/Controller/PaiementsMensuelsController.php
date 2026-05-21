<?php

namespace App\Controller;

use App\Entity\PaiementsMensuels;
use App\Entity\RBTBailleur;
use App\Repository\PaiementsMensuelsRepository;
use App\Repository\LocatairesRepository;
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
    public function index(PaiementsMensuelsRepository $repoPaiements, LocatairesRepository $locataireRepo, RBTBailleurRepository $repoRBT): Response {

        $paiements = $repoPaiements->findBy([], ['date' => 'DESC']);
        $firstPaiements = $repoPaiements->findFirstPaiementsIds();
        $locataires = $locataireRepo->findAll();

        // transformer en tableau simple [1, 5, 8, ...]
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
                'charge' => $charge,
                'PS' => $PS,
                'RBT'      => $RBT,
            ];
        }

        return $this->render('paiements_mensuels/index.html.twig', [
            'paiements' => $result,
            'firstPaiementsIds' => $firstPaiementsIds,
            'locataires' => $locataires,
            'locataire' => null,
            'mois' => null
        ]);
    }

    #[Route('/paiements/new', name: 'paiements_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $paiement = new PaiementsMensuels();

        $form = $this->createForm(PaiementMensuelType::class, $paiement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($paiement);
            $em->flush();

            return $this->redirectToRoute('app_paiements_mensuels');
        }

        return $this->render('paiements_mensuels/new.html.twig', [
            'form' => $form->createView(),
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

        $em->remove($paiement);
        $em->flush();

        $this->addFlash('success', 'Paiement supprimé');

        return $this->redirectToRoute('app_paiements_mensuels');
    }
}

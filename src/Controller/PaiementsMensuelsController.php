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
    public function index(
        Request $request,
        PaiementsMensuelsRepository $repoPaiements,
        LocatairesRepository $locataireRepo,
        RBTBailleurRepository $repoRBT
    ): Response {

        $locataireId = $request->query->get('locataireId');

        if ($locataireId) {

            $paiements = $repoPaiements->findBy(
                ['LocatairesID' => $locataireId],
                ['date' => 'DESC']
            );

        } else {

            $paiements = $repoPaiements->findBy(
                [],
                ['date' => 'DESC']
            );
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

        return $this->render('paiements_mensuels/index.html.twig', [
            'paiements' => $result,
            'firstPaiementsIds' => $firstPaiementsIds,
            'locataires' => $locataires,
            'locataireId' => $locataireId,
            'mois' => null
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

        $em->remove($paiement);
        $em->flush();

        $this->addFlash('success', 'Paiement supprimé');

        return $this->redirectToRoute('app_paiements_mensuels');
    }
}

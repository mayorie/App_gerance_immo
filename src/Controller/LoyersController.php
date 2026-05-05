<?php

namespace App\Controller;

use App\Entity\LoyersHC;
use App\Repository\LocatairesRepository;
use App\Repository\LoyersHCRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class LoyersController extends AbstractController
{
    #[Route('/loyers', name: 'app_loyers')]
    public function index(
        Request $request,
        LoyersHCRepository $repo,
        LocatairesRepository $locataireRepo
    ): Response {

        $locataires = $locataireRepo->findAll();

        $locataireId = $request->query->get('locataireId');
        $locataire = null;

        if ($locataireId) {
            $locataire = $locataireRepo->find($locataireId);
            $loyers = $repo->findBy(['LocatairesID' => $locataire]);
        } else {
            $loyers = $repo->findAll();
        }

        return $this->render('loyers/index.html.twig', [
            'loyers' => $loyers,
            'locataires' => $locataires,
            'locataire' => $locataire
        ]);
    }

    #[Route('/loyers/batch', name: 'loyers_batch')]
    public function batch(Request $request, EntityManagerInterface $em, LocatairesRepository $repo) {
        // locataires actifs
        $locataires = $repo->findBy([
            'a_quitte_le_logement' => false
        ]);

        if ($request->isMethod('POST')) {

            $montants = $request->request->all('montant');
            $dates = $request->request->all('date');

            foreach ($locataires as $locataire) {

                $id = $locataire->getId();

                if (!empty($montants[$id])) {

                    $loyer = new LoyersHC();
                    $loyer->setMontant($montants[$id]);

                    $date = !empty($dates[$id])
                        ? new \DateTime($dates[$id])
                        : new \DateTime();

                    $loyer->setDateMES($date);
                    $loyer->setLocatairesID($locataire);

                    $em->persist($loyer);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_loyers');
        }

        return $this->render('loyers/batch.html.twig', [
            'locataires' => $locataires
        ]);
    }

    #[Route('/loyers/edit/{id}', name: 'loyers_edit')]
    public function edit(LoyersHC $loyer, Request $request, EntityManagerInterface $em): Response {

        if ($request->isMethod('POST')) {

            $montant = $request->request->get('montant');
            $date = $request->request->get('date');

            if ($montant) {
                $loyer->setMontant($montant);
            }

            if ($date) {
                $loyer->setDateMES(new \DateTime($date));
            }

            $em->flush();

            return $this->redirectToRoute('app_loyers');
        }

        return $this->render('loyers/edit.html.twig', [
            'loyer' => $loyer
        ]);
    }

    #[Route('/loyers/delete/{id}', name: 'loyers_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        LoyersHC $loyer,
        EntityManagerInterface $em
    ): Response {

        if (!$this->isCsrfTokenValid('delete'.$loyer->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('app_loyers');
        }

        $locataire = $loyer->getLocatairesID();

        // compter les loyers du locataire
        $nbLoyers = $em->getRepository(LoyersHC::class)->count([
            'LocatairesID' => $locataire
        ]);

        // si c'est le dernier → on bloque
        if ($nbLoyers <= 1) {
            $this->addFlash('error', 'Impossible de supprimer : ce locataire doit avoir au moins un loyer.');
            return $this->redirectToRoute('app_loyers');
        }

        $em->remove($loyer);
        $em->flush();

        $this->addFlash('success', 'Loyer supprimé avec succès.');

        return $this->redirectToRoute('app_loyers');
    }
}

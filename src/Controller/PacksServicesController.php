<?php

namespace App\Controller;

use App\Entity\PacksServices;
use App\Repository\PacksServicesRepository;
use App\Repository\LocatairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class PacksServicesController extends AbstractController
{
    #[Route('/packs/services', name: 'app_packs_services')]
    public function index(
        Request $request,
        PacksServicesRepository $repo,
        LocatairesRepository $locataireRepo
    ): Response {

        $locataires = $locataireRepo->findAll();

        $locataireId = $request->query->get('locataireId');
        $locataire = null;
        $page = $request->query->getInt('page', 1);
        $limit = 12;

        if ($locataireId) {
            $locataire = $locataireRepo->find($locataireId);
            $packsServices = $repo->findByLocatairePaginated($locataire, $page, $limit);
            $totalPacks = $repo->countByLocataire($locataire);
        } else {
            $packsServices = $repo->findAllPaginated($page, $limit);
            $totalPacks = $repo->countAll();
        }

        $totalPages = ceil($totalPacks / $limit);

        return $this->render('packs_services/index.html.twig', [
            'packsServices' => $packsServices,
            'locataires' => $locataires,
            'locataire' => $locataire,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    #[Route('/packs/services/batch', name: 'packs_services_batch')]
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

                    $pack = new PacksServices();
                    $pack->setMontant($montants[$id]);

                    $date = !empty($dates[$id])
                        ? new \DateTime($dates[$id])
                        : new \DateTime();

                    $pack->setDateMES($date);
                    $pack->setLocatairesID($locataire);

                    $em->persist($pack);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_packs_services');
        }

        return $this->render('packs_services/batch.html.twig', [
            'locataires' => $locataires
        ]);
    }

    #[Route('/packs/services/edit/{id}', name: 'packs_services_edit')]
    public function edit(PacksServices $pack, Request $request, EntityManagerInterface $em): Response {

        if ($request->isMethod('POST')) {

            $montant = $request->request->get('montant');
            $date = $request->request->get('date');

            if ($montant) {
                $pack->setMontant($montant);
            }

            if ($date) {
                $pack->setDateMES(new \DateTime($date));
            }

            $em->flush();

            return $this->redirectToRoute('app_packs_services');
        }

        return $this->render('packs_services/edit.html.twig', [
            'pack' => $pack
        ]);
    }

    #[Route('/packs/services/delete/{id}', name: 'packs_services_delete', methods: ['POST'])]
    public function delete(Request $request, PacksServices $pack, EntityManagerInterface $em): Response {

        if (!$this->isCsrfTokenValid('delete'.$pack->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('app_packs_services');
        }

        $locataire = $pack->getLocatairesID();

        // compter les packs services du locataire
        $nbPacks = $em->getRepository(PacksServices::class)->count([
            'LocatairesID' => $locataire
        ]);

        // si c'est le dernier → on bloque
        if ($nbPacks <= 1) {
            $this->addFlash('error', 'Impossible de supprimer : ce locataire doit avoir au moins un pack service.');
            return $this->redirectToRoute('app_packs_services');
        }

        $em->remove($pack);
        $em->flush();

        $this->addFlash('success', 'Pack service supprimé avec succès.');

        return $this->redirectToRoute('app_packs_services');
    }
}
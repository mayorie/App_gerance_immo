<?php

namespace App\Controller;

use App\Entity\Locataires;
use App\Repository\LocatairesRepository;
use App\Repository\LoyersHCRepository;
use App\Repository\PacksServicesRepository;
use App\Repository\ProvisionsPourChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class PaiementApiController extends AbstractController
{
    #[Route('/montants-locataire', name: 'montants_locataire')]
    public function montantsLocataire(
        Request $request,
        LocatairesRepository $locatairesRepo,
        LoyersHCRepository $loyersRepo,
        PacksServicesRepository $packsRepo,
        ProvisionsPourChargesRepository $chargesRepo,
    ): JsonResponse
    {
        $locataireId = $request->query->get('locataire');
        $date = $request->query->get('date');

        if (!$locataireId || !$date) {
            return $this->json([
                'error' => 'Paramètres manquants'
            ], 400);
        }

        $locataire = $locatairesRepo->find($locataireId);

        if (!$locataire) {
            return $this->json([
                'error' => 'Locataire introuvable'
            ], 404);
        }

        $datePaiement = new \DateTime($date);

        $loyer = $loyersRepo->findLoyerPourDate($locataire, $datePaiement);
        $charge = $chargesRepo->findChargePourDate($locataire, $datePaiement);
        $pack = $packsRepo->findPackServicePourDate($locataire, $datePaiement);

        return $this->json([
            'loyer' => $loyer?->getMontant() ?? 0,
            'charge' => $charge?->getMontant() ?? 0,
            'pack' => $pack?->getMontant() ?? 0,
        ]);
    }
}

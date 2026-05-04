<?php

namespace App\Controller;

use App\Entity\ProvisionsPourCharges;
use App\Repository\LocatairesRepository;
use App\Repository\ProvisionsPourChargesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class ChargesController extends AbstractController
{
    #[Route('/charges', name: 'app_charges')]
    public function index(
        Request $request,
        ProvisionsPourChargesRepository $repo,
        LocatairesRepository $locataireRepo
    ): Response {

        $locataires = $locataireRepo->findAll();

        $locataireId = $request->query->get('locataireId');
        $locataire = null;

        if ($locataireId) {
            $locataire = $locataireRepo->find($locataireId);
            $charges = $repo->findBy(['LocatairesID' => $locataire]);
        } else {
            $charges = $repo->findAll();
        }

        return $this->render('charges/index.html.twig', [
            'charges' => $charges,
            'locataires' => $locataires,
            'locataire' => $locataire
        ]);
    }

    #[Route('/charges/batch', name: 'charges_batch')]
    public function batch(Request $request, EntityManagerInterface $em, LocatairesRepository $repo)
    {
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

                    $charge = new ProvisionsPourCharges();
                    $charge->setMontant($montants[$id]);

                    $date = !empty($dates[$id])
                        ? new \DateTime($dates[$id])
                        : new \DateTime();

                    $charge->setDateMES($date);
                    $charge->setLocatairesID($locataire);

                    $em->persist($charge);
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_charges');
        }

        return $this->render('charges/batch.html.twig', [
            'locataires' => $locataires
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Locataires;
use App\Entity\Commentaires;
use App\Entity\LoyersHC;
use App\Entity\ProvisionsPourCharges;
use App\Entity\PacksServices;
use App\Repository\LocatairesRepository;
use App\Form\LocataireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class LocatairesController extends AbstractController
{
    #[Route('/locataires', name: 'app_locataires')]
    public function index(LocatairesRepository $repoLocataires): Response
    {
        $Locataires = $repoLocataires->findAll();
        return $this->render('locataires/index.html.twig', [
            'locataires' => $Locataires
        ]);
    }

    #[Route('/locataires/new', name: 'locataires_new')]
    public function form(Request $request, EntityManagerInterface $em): Response
    {
        $locataire = new Locataires();

        $form = $this->createForm(LocataireType::class, $locataire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($locataire);

            // LOYER
            $loyerMontant = $form->get('loyer_montant')->getData();
            $loyerDate = $form->get('loyer_date')->getData() ?? $debutBail;

            if ($loyerMontant) {
                $loyer = new LoyersHC();
                $loyer->setMontant($loyerMontant);
                $loyer->setDateMES($loyerDate);
                $loyer->setLocatairesID($locataire);

                $em->persist($loyer);
            }

            // CHarges
            $chargeMontant = $form->get('charge_montant')->getData();
            $chargeDate = $form->get('charge_date')->getData() ?? $debutBail;

            if ($chargeMontant) {
                $charge = new ProvisionsPourCharges();
                $charge->setMontant($chargeMontant);
                $charge->setDateMES($chargeDate);
                $charge->setLocatairesID($locataire);

                $em->persist($charge);
            }

            // Packs Services (PS)
            $PSMontant = $form->get('PS_montant')->getData();
            $PSDate = $form->get('PS_date')->getData() ?? $debutBail;

            if ($PSMontant) {
                $PS = new PacksServices();
                $PS->setMontant($PSMontant);
                $PS->setDateMES($PSDate);
                $PS->setLocatairesID($locataire);

                $em->persist($PS);
            }

            //COMMENTAIRE
            $commentaireTexte = $form->get('commentaire')->getData();

            if ($commentaireTexte) {
                $commentaire = new Commentaires();
                $commentaire->setTexte($commentaireTexte);

                $em->persist($commentaire);
            }

            $em->flush();

            return $this->redirectToRoute('app_locataires');
        }

        return $this->render('locataires/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

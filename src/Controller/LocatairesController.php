<?php

namespace App\Controller;

use App\Entity\Locataires;
use App\Entity\Garants;
use App\Entity\GarantsVisale;
use App\Entity\GarantsPhysiques;
use App\Entity\Commentaires;
use App\Entity\LoyersHC;
use App\Entity\ProvisionsPourCharges;
use App\Entity\PacksServices;
use App\Repository\LocatairesRepository;
use App\Form\LocataireType;
use App\Form\LocataireEditType;
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

            $garantsData = $form->get('garantsPhysiques')->getData();

            foreach ($garantsData as $garantPhysique) {



                $garant = new Garants();
                $garant->setType('PHY'); // 🔥 important
                $garant->setLocatairesID($locataire);

                $garant->setGarantsPhysiques($garantPhysique);
                $garantPhysique->setGarantsID($garant);

                $em->persist($garant);
                $em->persist($garantPhysique);
            }

            $garantsVisaleData = $form->get('garantsVisale')->getData();

            foreach ($garantsVisaleData as $garantVisale) {

                $garant = new Garants();
                $garant->setType('VIS'); // 🔥 VISALE
                $garant->setLocatairesID($locataire);

                $garant->setGarantsVisale($garantVisale);
                $garantVisale->setGarantsID($garant);

                $em->persist($garant);
                $em->persist($garantVisale);
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

    #[Route('/locataires/edit/{id}', name: 'locataires_edit')]
    public function edit(Locataires $locataire, Request $request, EntityManagerInterface $em): Response
    {
        $commentaire = $locataire->getCommentaires()?->getTexte();

        $loyer = $locataire->getLatestLoyer();
        $montantLoyerBase = $loyer->getMontant();
        $dateLoyerBase = $loyer->getDateMES();

        $PS = $locataire->getLatestPackServices();
        $montantPSBase = $PS->getMontant();
        $datePSBase = $PS->getDateMES();

        $charge = $locataire->getLatestCharge();
        $montantChargeBase = $charge->getMontant();
        $dateChargeBase = $charge->getDateMES();

        $garants = $locataire->getGarants();

        $garantsPhysiques = [];
        $garantsVisale = [];

        foreach($garants as $garant) {
            if ($garant->getType() === 'PHY') {
                $garantsPhysiques[] = $garant->getGarantsPhysiques();
            }

            if ($garant->getType() === 'VIS') {
                $garantsVisale[] = $garant->getGarantsVisale();
            }
        }

        $form = $this->createForm(LocataireEditType::class, $locataire, [
            'commentaire' => $commentaire,
            'loyer' => $loyer,
            'PS' => $PS,
            'charge' => $charge,
            'garantsPhysiques' => $garantsPhysiques,
            'garantsVisale' => $garantsVisale,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // COMMENTAIRE
            $texte = $form->get('commentaire')->getData();

            if ($texte) {
                if (!$locataire->getCommentaires()) {
                    $commentaire = new Commentaires();
                    $commentaire->setLocatairesID($locataire);
                } else {
                    $commentaire = $locataire->getCommentaires();
                }

                $commentaire->setTexte($texte);
                $em->persist($commentaire);
            }

            //Modification de loyer
            $montantLoyerModif = $form->get('loyer_montant')->getData();
            $dateLoyerModif = $form->get('loyer_date')->getData();
            if($montantLoyerBase!=$montantLoyerModif)
                $loyer->setMontant($montantLoyerModif);
            if($dateLoyerBase!=$dateLoyerModif)
                $loyer->setDateMES($dateLoyerModif);            
            
            //Modification de PS
            $montantPSModif = $form->get('PS_montant')->getData();
            $datePSModif = $form->get('PS_date')->getData();
            if($montantPSBase!=$montantPSModif)
                $PS->setMontant($montantPSModif);
            if($datePSBase!=$datePSModif)
                $PS->setDateMES($datePSModif);            
            
            //Modification de Charge
            $montantChargeModif = $form->get('charge_montant')->getData();
            $dateChargeModif = $form->get('charge_date')->getData();
            if($montantChargeBase!=$montantChargeModif)
                $charge->setMontant($montantChargeModif);
            if($dateChargeBase!=$dateChargeModif)
                $charge->setDateMES($dateChargeModif);

            $em->flush();

            return $this->redirectToRoute('app_locataires');
        }

        return $this->render('locataires/edit.html.twig', [
            'form' => $form->createView(),
            'locataire' => $locataire
        ]);
    }

    #[Route('/locataires/delete/{id}', name: 'locataire_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Locataires $locataire,
        EntityManagerInterface $em
    ): Response {
        // 🔐 Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete' . $locataire->getId(), $request->request->get('_token'))) {

            // 🧹 Suppression du locataire
            $em->remove($locataire);
            $em->flush();

            $this->addFlash('success', 'Locataire supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }

        return $this->redirectToRoute('app_locataires');
    }
}

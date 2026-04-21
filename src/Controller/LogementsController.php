<?php

namespace App\Controller;

use App\Entity\Logements;
use App\Form\LogementType;
use App\Repository\LogementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commentaires;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class LogementsController extends AbstractController
{
    
    #[Route('/logements', name: 'app_logements')]
    public function index(LogementsRepository $repoLogements): Response
    {
        $logements = $repoLogements->findAll();
        return $this->render('logements/index.html.twig', [
            'logements' => $logements
        ]);
    }

    #[Route('/logements/new', name: 'logement_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $logement = new Logements();

        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($logement);

            // 🔥 récupération du champ commentaire (mapped => false)
            $commentaireTexte = $form->get('commentaire')->getData();

            if ($commentaireTexte) {
                $commentaire = new Commentaires();
                $commentaire->setTexte($commentaireTexte);

                // si relation existe
                $commentaire->setLogementsID($logement);

                $em->persist($commentaire);
            }

            $em->flush();

            return $this->redirectToRoute('app_logements');
        }

        return $this->render('logements/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logements/edit/{id}', name: 'logement_edit')]
    public function edit(int $id): Response
    {
        return $this->render('logements/edit.html.twig', [
            'id' => $id
        ]);
    }

    #[Route('/logements/delete/{id}', name: 'logement_delete', methods: ['POST'])]
    public function delete(int $id, LogementsRepository $repo, EntityManagerInterface $em, Request $request): Response
    {
        $logement = $repo->find($id);

        if (!$logement) {
            throw $this->createNotFoundException('Logement introuvable');
        }

        // 🔐 sécurité CSRF
        if ($this->isCsrfTokenValid('delete'.$logement->getId(), $request->request->get('_token'))) {

            $em->remove($logement);
            $em->flush();
        }

        return $this->redirectToRoute('app_logements');
    }

    
}

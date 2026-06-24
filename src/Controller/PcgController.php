<?php

namespace App\Controller;

use App\Entity\Pcg;
use App\Repository\PcgRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PcgController extends AbstractController
{
    #[Route('/pcg', name: 'app_pcg')]
    public function index(PcgRepository $repo): Response
    {
        $pcgs = $repo->findAll();

        return $this->render('pcg/index.html.twig', [
            'pcgs' => $pcgs
        ]);
    }

    #[Route('/pcg/import', name: 'pcg_import')]
    public function import(Request $request, EntityManagerInterface $em, PcgRepository $repo): Response
    {
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $file */
            $file = $request->files->get('csv_file');

            if ($file) {
                $handle = fopen($file->getPathname(), 'r');
                
                // Skip header line
                fgetcsv($handle, 0, ';');
                
                $count = 0;
                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    if (isset($data[0]) && isset($data[1])) {
                        $compte = trim($data[0]);
                        $libelle = trim($data[1]);
                        
                        // Check if compte already exists
                        $existing = $repo->findOneBy(['compte' => $compte]);
                        
                        if (!$existing && !empty($compte) && !empty($libelle)) {
                            $pcg = new Pcg();
                            $pcg->setCompte($compte);
                            $pcg->setLibelle($libelle);
                            $em->persist($pcg);
                            $count++;
                        }
                    }
                }
                
                fclose($handle);
                $em->flush();
                
                $this->addFlash('success', "$count comptes PCG importés avec succès.");
                
                return $this->redirectToRoute('app_pcg');
            }
        }

        return $this->render('pcg/import.html.twig');
    }

    #[Route('/pcg/edit/{id}', name: 'pcg_edit')]
    public function edit(Pcg $pcg, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $compte = $request->request->get('compte');
            $libelle = $request->request->get('libelle');

            if ($compte) {
                $pcg->setCompte($compte);
            }

            if ($libelle) {
                $pcg->setLibelle($libelle);
            }

            $em->flush();

            return $this->redirectToRoute('app_pcg');
        }

        return $this->render('pcg/edit.html.twig', [
            'pcg' => $pcg
        ]);
    }

    #[Route('/pcg/delete/{id}', name: 'pcg_delete', methods: ['POST'])]
    public function delete(Request $request, Pcg $pcg, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$pcg->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('app_pcg');
        }

        $em->remove($pcg);
        $em->flush();

        $this->addFlash('success', 'Compte PCG supprimé avec succès.');

        return $this->redirectToRoute('app_pcg');
    }
}

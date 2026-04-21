<?php

namespace App\Controller;

use App\Repository\LocatairesRepository;
use App\Repository\LogementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestBDController extends AbstractController
{
    #[Route('/testBD', name: 'app_test_b_d')]
    public function index(LocatairesRepository $repoLocataires, LogementsRepository $repoLogements): Response
    {
        $locataires = $repoLocataires->findAll();
        $logements = $repoLogements->findAll();
        return $this->render('test_bd/index.html.twig', [
            'controller_name' => 'TestBDController',
            'locataires' => $locataires,
            'logements' => $logements
        ]);
    }
}

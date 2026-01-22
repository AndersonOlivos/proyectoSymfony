<?php

namespace App\Controller;

use App\Service\PadelApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BaseLoggedController extends AbstractController
{
    #[Route('/app', name: 'app_app')]
    public function index(PadelApiService $padelApiService): Response
    {
        $jugadores = $padelApiService->getJugadoresTopRanking(20);

        return $this->render('app/index.html.twig', [
            'jugadores' => $jugadores
        ]);
    }
}

<?php

namespace App\Controller;

use App\Service\PadelApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppController extends AbstractController
{
    #[Route('/app', name: 'app_app')]
    public function index(PadelApiService $padelApiService): Response
    {
        $jugadores = $padelApiService->getJugadoresTopRanking(20);

        return $this->render('app/index.html.twig', [
            'jugadores' => $jugadores
        ]);
    }

    #[Route('/app/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, PadelApiService $padelApiService): Response
    {
        $query = $request->query->get('q', '');

        if (empty($query)) {
            $jugadores = $padelApiService->getJugadoresTopRanking(20);
        } else {
            $jugadores = $padelApiService->buscarJugadores($query);
        }

        return $this->render('app/_lista_jugadores.html.twig', [
            'jugadores' => $jugadores
        ]);
    }
}

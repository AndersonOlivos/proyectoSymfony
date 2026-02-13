<?php

namespace App\Controller\Admin;

use App\Repository\JugadorRepository;
use App\Repository\RankingGeneralRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(JugadorRepository $jugadorRepository, ReviewRepository $reviewRepository, RankingGeneralRepository $rankingGeneralRepository): Response
    {

        $jugadoresRegistrados = $jugadorRepository->numeroJugadoresRegistrados()['numeroJugadoresRegistrados'];
        $valoracionesTotales = $reviewRepository->valoracionesTotales()['numeroValoracionesTotales'];
        $rankingsActivos = $rankingGeneralRepository->numeroRankingsActivos()['numeroRankingsActivos'];
        $nombreJugadorMasVotado = $jugadorRepository->nombreJugadorMasVotado()['nombre'];
        $jugadoresMejorValoracion = $reviewRepository->jugadoresMejorValoracion(20);
        $rankingsMasActivos = $rankingGeneralRepository->rankingsMasActivos();
        $resumenRapido = $reviewRepository->resumenRapido();

        return $this->render('/admin/admin_dashboard/index.html.twig', [
            'jugadoresRegistrados' => $jugadoresRegistrados,
            'valoracionesTotales' => $valoracionesTotales,
            'rankingsActivos' => $rankingsActivos,
            'nombreJugadorMasVotado' => $nombreJugadorMasVotado,
            'jugadoresMejorValoracion' => $jugadoresMejorValoracion,
            'rankingsMasActivos' => $rankingsMasActivos,
            'resumenRapido' => $resumenRapido,
        ]);
    }
}

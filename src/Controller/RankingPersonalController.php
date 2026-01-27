<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\OrdenRankingPersonalRepository;
use App\Repository\RankingGeneralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RankingPersonalController extends AbstractController
{
    #[Route('/ranking/personal/{id_ranking}', name: 'app_ranking_personal')]
    public function index(int $id_ranking, RankingGeneralRepository $rankingGeneralRepository,
                          OrdenRankingPersonalRepository $ordenRankingPersonalRepository,
                            CategoriaRepository $categoriaRepository): Response
    {

        $datos_ranking = $rankingGeneralRepository->obtenerRankingGeneral($id_ranking);

        $top5ranking = $rankingGeneralRepository->obtenerTopXRankingGeneral($id_ranking, 5);

        $orden_jugadores = [];

        if ($datos_ranking[0]['usuario_participa'] > 0){
            $orden_jugadores = $ordenRankingPersonalRepository->obtenerOrdenRankingPersonal($this->getUser()->getId(), $id_ranking);
        }

        $jugadores_categoria = $categoriaRepository->obtenerJugadoresPorCategoria($datos_ranking[0]['id_categoria']);

        return $this->render('ranking_personal/index.html.twig', [
            'datos_ranking' => $datos_ranking[0],
            'orden_jugadores' => $orden_jugadores,
            'jugadores_categoria' => $jugadores_categoria,
            'top5ranking' => $top5ranking,
        ]);
    }
}

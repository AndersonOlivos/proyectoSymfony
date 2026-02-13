<?php

namespace App\Controller;

use App\Entity\OrdenRankingPersonal;
use App\Entity\RankingPersonal;
use App\Repository\CategoriaRepository;
use App\Repository\JugadorRepository;
use App\Repository\OrdenRankingPersonalRepository;
use App\Repository\RankingGeneralRepository;
use App\Repository\RankingPersonalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RankingPersonalController extends AbstractController
{
    #[Route('/ranking/personal/{id_ranking}', name: 'app_ranking_personal')]
    public function index(int $id_ranking, RankingGeneralRepository $rankingGeneralRepository,
                          OrdenRankingPersonalRepository $ordenRankingPersonalRepository,
                            CategoriaRepository $categoriaRepository): Response
    {

        $datos_ranking = $rankingGeneralRepository->obtenerRankingGeneral($id_ranking, $this->getUser()->getId());

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

    #[Route('/ranking/personal/guardar/{id_ranking}', name: 'app_ranking_personal_guardar', methods: ['POST'])]
    public function guardarRankingPersonal(int $id_ranking, Request $request,
                                           RankingGeneralRepository $rankingGeneralRepository,
                                           RankingPersonalRepository $rankingPersonalRepository,
                                           JugadorRepository $jugadorRepository,
                                           OrdenRankingPersonalRepository $ordenRankingPersonalRepository,
                                           EntityManagerInterface $entityManager): JsonResponse{
        $user = $this->getUser();
        $rankingGeneral = $rankingGeneralRepository->find($id_ranking);

        $body = json_decode($request->getContent(), true);

        $idsJugadores = $body['orden'] ?? [];

        $rankingPersonal = $rankingPersonalRepository->findOneBy([
            'rankingGeneral' => $rankingGeneral,
            'usuario' => $user,
            'activo' => true
        ]);

        if (!$rankingPersonal) {
            $rankingPersonal = new RankingPersonal();
            $rankingPersonal->setRankingGeneral($rankingGeneral);
            $rankingPersonal->setUsuario($user);
            $rankingPersonal->setActivo(true);
            $entityManager->persist($rankingPersonal);
        } else {
            $antiguosOrdenes = $ordenRankingPersonalRepository->findBy(['rankingPersonal' => $rankingPersonal]);
            foreach ($antiguosOrdenes as $viejo) {
                $entityManager->remove($viejo);
            }
            $entityManager->flush();
        }

        foreach ($idsJugadores as $index => $idJugador) {
            $jugador = $jugadorRepository->find($idJugador);

            if ($jugador) {
                $orden = new OrdenRankingPersonal();
                $orden->setRankingPersonal($rankingPersonal);
                $orden->setJugador($jugador);
                $orden->setPosicion($index + 1);

                $entityManager->persist($orden);
            }
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'ok', 'message' => 'Ranking guardado correctamente']);
    }
}

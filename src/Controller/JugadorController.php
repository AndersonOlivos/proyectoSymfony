<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\CategoriaRepository;
use App\Repository\JugadorRepository;
use App\Repository\RankingGeneralRepository;
use App\Repository\ReviewRepository;
use App\Service\JugadorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JugadorController extends AbstractController
{
    #[Route('/jugador/{id}', name: 'app_jugador')]
    public function index(int $id, JugadorService $jugadorService,
                          JugadorRepository $jugadorRepository,
                          CategoriaRepository $categoriaRepository,
                          ReviewRepository $reviewRepository,
                          RankingGeneralRepository $rankingGeneralesRepository,
                          EntityManagerInterface $entityManager,
                          Request $request): Response
    {

        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }

        $jugador = $jugadorService -> obtenerDatosJugador($id);
        $review = $reviewRepository -> obtenerReviewJugador($id, $this->getUser()->getId());
        $reviews = $reviewRepository -> obtenerAllReviewsJugador($id);
        $mediaReviews = $reviewRepository -> obtenerMediaReviews($id);
        $categorias = $categoriaRepository -> obtenerCategoriasJugador($id);
        $rankingsGenerales = $rankingGeneralesRepository -> obtenerRankingGeneralesJugador($categorias);
        $esFavorito = $this -> getUser() -> getJugadoresFavoritos() -> contains($jugadorRepository->find($id));

        $datosReviews = [];
        foreach($reviews as $reviewUsuario){
            $usuario = $reviewUsuario -> getUsuario();
            if($usuario != null){
                $nombreUsuario = $usuario -> getUsername();
                $puntuacion = $reviewUsuario -> getPuntuacion();
                $texto = $reviewUsuario -> getTexto();
            }
            $datosReviews[] = [
                'id' => $reviewUsuario -> getId(),
                'nombreUsuario' => $nombreUsuario ?? 'Desconocido',
                'puntuacion' => $puntuacion ?? 0,
                'texto' => $texto ?? '',
            ];
        }

        $sort = $request->query->get('sort', 'recientes'); // Por defecto recientes

        usort($datosReviews, function ($a, $b) use ($sort) {
            if ($sort === 'mejores') {
                return $b['puntuacion'] <=> $a['puntuacion'];
            } else {
                return $b['id'] <=> $a['id'];
            }
        });

        if($request->isMethod('POST')) {
            if($review == null){
                $review = new Review();
                $review->setJugador($jugadorRepository->find($id));
                $review->setUsuario($this->getUser());
            }
            $puntuacion = $request->request->get('puntuacion');
            $texto = $request->request->get('comentario');
            $review -> setPuntuacion((int)$puntuacion);
            $review -> setTexto($texto);
            $entityManager->persist($review);
            $entityManager->flush();
            return $this->redirectToRoute('app_jugador', ['id' => $id]);
        }

        return $this->render('jugador/index.html.twig', [
            'jugador' => $jugador,
            'review' => $review,
            'reviews' => $datosReviews,
            'mediaReviews' => $mediaReviews,
            'categorias' => $categorias,
            'rankingsGenerales' => $rankingsGenerales,
            'esFavorito' => $esFavorito,
            'currentSort' => $sort
        ]);
    }

    #[Route('/jugador/{id}/favorito', name: 'app_jugador_toggle_fav', methods: ['POST'])]
    public function toggleFavorito(int $id, JugadorRepository $jugadorRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) return $this->redirectToRoute('app_login');

        $jugador = $jugadorRepository->find($id);

        if ($user->getJugadoresFavoritos()->contains($jugador)) {
            $user->removeJugadoresFavorito($jugador);
        } else {
            $user->addJugadoresFavorito($jugador);
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_jugador', ['id' => $id]);
    }
}

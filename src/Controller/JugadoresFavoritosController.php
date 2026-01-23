<?php

namespace App\Controller;

use App\Repository\JugadoresFavoritosRepository;
use App\Repository\UsuarioRepository;
use App\Service\JugadorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JugadoresFavoritosController extends AbstractController
{
    #[Route('/jugadores/favoritos', name: 'app_jugadores_favoritos')]
    public function index(JugadoresFavoritosRepository $jugadoresFavoritosRepository,
                            JugadorService $jugadorService): Response
    {

        $jugadoresFavoritos = $jugadoresFavoritosRepository -> obtenerJugadoresFavoritosActivos($this -> getUser() -> getId());

        $datosJugadoresFavoritos = [];

        if(!empty($jugadoresFavoritos)){
            foreach ($jugadoresFavoritos as $favorito) {
                $datosJugadoresFavoritos[] = $jugadorService -> obtenerDatosJugador($favorito-> getId());
            }
        }


        return $this->render('jugadores_favoritos/index.html.twig', [
            'jugadoresFavoritosActivos' => $datosJugadoresFavoritos,
        ]);
    }
}

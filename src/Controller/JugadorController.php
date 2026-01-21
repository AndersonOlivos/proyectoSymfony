<?php

namespace App\Controller;

use App\Service\JugadorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JugadorController extends AbstractController
{
    #[Route('/jugador/{id}', name: 'app_jugador')]
    public function index(int $id, JugadorService $jugadorService): Response
    {

        $jugador = $jugadorService -> obtenerDatosJugador($id);

        return $this->render('jugador/index.html.twig', [
            'jugador' => $jugador,
        ]);
    }
}

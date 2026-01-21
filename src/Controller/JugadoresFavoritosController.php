<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JugadoresFavoritosController extends AbstractController
{
    #[Route('/jugadores/favoritos', name: 'app_jugadores_favoritos')]
    public function index(): Response
    {
        return $this->render('jugadores_favoritos/index.html.twig', [
            'controller_name' => 'JugadoresFavoritosController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JugadorCategoriaController extends AbstractController
{
    #[Route('/jugador/categoria', name: 'app_jugador_categoria')]
    public function index(): Response
    {
        return $this->render('jugador_categoria/index.html.twig', [
            'controller_name' => 'JugadorCategoriaController',
        ]);
    }
}

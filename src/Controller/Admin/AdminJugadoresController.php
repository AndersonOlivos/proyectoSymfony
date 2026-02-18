<?php

namespace App\Controller\Admin;

use App\Entity\Jugador;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/jugadores')]
class AdminJugadoresController extends AbstractController
{
    #[Route('/', name: 'admin_jugador_index', methods: ['GET'])]
    public function index(Request $request, JugadorRepository $jugadorRepository): Response
    {
        $jugadores = $jugadorRepository->findAll();

        return $this->render('admin/jugadores/index.html.twig', [
            'jugadores' => $jugadores
        ]);
    }
}

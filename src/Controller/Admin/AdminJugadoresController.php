<?php

namespace App\Controller\Admin;

use App\Entity\Jugador;
use App\Form\JugadorType;
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
        // Obtener parámetros de la URL (busqueda y pagina)
        $busqueda = $request->query->get('q');
        $pagina = $request->query->getInt('page', 1);

        // Llamamos a nuestro método personalizado del repositorio
        $resultado = $jugadorRepository->buscarConFiltros($busqueda, $pagina);

        return $this->render('admin/jugadores/index.html.twig', [
            'jugadores' => $resultado['datos'],
            'maxPaginas' => $resultado['maxPaginas'],
            'paginaActual' => $resultado['paginaActual'],
            'busqueda' => $busqueda,
            'total' => $resultado['total']
        ]);
    }

    #[Route('/new', name: 'admin_jugador_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jugador = new Jugador();
        $jugador->setActivo(true); // Por defecto activo
        $form = $this->createForm(JugadorType::class, $jugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jugador);
            $entityManager->flush();

            return $this->redirectToRoute('admin_jugador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/jugador/new.html.twig', [
            'jugador' => $jugador,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_jugador_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jugador $jugador, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JugadorType::class, $jugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_jugador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/jugador/edit.html.twig', [
            'jugador' => $jugador,
            'form' => $form->createView(),
        ]);
    }

    // SOFT DELETE: No borra, solo cambia el estado
    #[Route('/{id}/toggle-status', name: 'admin_jugador_toggle', methods: ['POST'])]
    public function toggleStatus(Request $request, Jugador $jugador, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle'.$jugador->getId(), $request->request->get('_token'))) {
            // Invierte el estado: Si es true pasa a false, y viceversa
            $jugador->setActivo(!$jugador->isActivo());
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_jugador_index', [], Response::HTTP_SEE_OTHER);
    }
}

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

    #[Route('/{id}/editar', name: 'admin_jugador_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jugador $jugador,
                         EntityManagerInterface $entityManager): Response
    {
        $nombre = $request->request->get('nombre');
        $sexo = $request->request->get('sexo');
        $puntos = $request->request->get('puntos');
        $altura = $request->request->get('altura');
        $imagen = $request->request->get('imagen_url');
        $esActivo = $request->request->get('activo') == '1';

        $jugador->setNombre($nombre);
        $jugador->setSexo($sexo);
        $jugador->setPuntos($puntos);
        $jugador->setAltura($altura);
        $jugador->setImagenUrl($imagen);
        $jugador->setActivo($esActivo);

        $entityManager->persist($jugador);
        $entityManager->flush();
        $this->addFlash('success', 'Jugador modificado correctamente');

        return $this->redirectToRoute('admin_jugador_index');
    }

    #[Route('/{id}/eliminar', name: 'admin_jugador_delete', methods: ['POST'])]
    public function delete(Request $request,
                           Jugador $jugador,
                           EntityManagerInterface $entityManager,
                           JugadorRepository $jugadorRepository): Response
    {

        if($jugadorRepository->noPuedeEliminarse($jugador->getId())['existe']){
            $this->addFlash('error', 'No se puede eliminar jugador. Existe alguna valoración o ranking.');
            return $this->redirectToRoute('admin_jugador_index');
        }

        $entityManager->remove($jugador);
        $entityManager->flush();

        $this->addFlash('success', 'Jugador eliminado correctamente');
        return $this->redirectToRoute('admin_jugador_index');
    }
}

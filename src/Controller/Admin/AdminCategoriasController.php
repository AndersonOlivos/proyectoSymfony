<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Entity\JugadorCategoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use App\Repository\JugadorCategoriaRepository;
use App\Repository\JugadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categorias')]
final class AdminCategoriasController extends AbstractController
{
    #[Route('/', name: 'admin_categoria_index', methods: ['GET'])]
    public function index(CategoriaRepository $categoriaRepository, JugadorRepository $jugadorRepository): Response
    {

        $datosCategorias = $categoriaRepository->obtenerDatosCategorias();

        $datosJugadores = $jugadorRepository->obtenerDatosJugadoresCategoria();


        return $this->render('admin/admin_categorias/index.html.twig', [
            'categorias' => $datosCategorias,
            'jugadores' => $datosJugadores
        ]);
    }

    #[Route('/nueva', name: 'admin_categoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, JugadorRepository $jugadorRepository): Response
    {
        $nombre = $request->request->get('nombre');
        $esActivo = $request->request->get('activo');
        $idsJugadores = $request->request->all()['jugadores'] ?? [];

        $categoria = new Categoria();
        $categoria->setNombre($nombre);
        $categoria->setActivo((bool) $esActivo);
        $entityManager->persist($categoria);

        foreach($idsJugadores as $id) {
            $jugador = $jugadorRepository->find($id);
            if ($jugador) {
                $jugadorCategoria = new JugadorCategoria();
                $jugadorCategoria->setCategoria($categoria);
                $jugadorCategoria->setJugador($jugador);
                $entityManager->persist($jugadorCategoria);
            }
        }

        $entityManager->flush();
        $this->addFlash('success', 'Categoria creada correctamente');
        return $this->redirectToRoute('admin_categoria_index');
    }

    #[Route('/{id}/editar', name: 'admin_categoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categoria, EntityManagerInterface $entityManager,
                         JugadorRepository $jugadorRepository,
                         JugadorCategoriaRepository $jugadorCategoriaRepository): Response
    {
        $nombre = $request->request->get('nombre');
        $esActivo = $request->request->get('activo') == '1';
        $idsJugadoresSeleccionados = $request->request->all()['jugadores'] ?? [];

        $categoria->setNombre($nombre);
        $categoria->setActivo($esActivo);

        foreach ($jugadorCategoriaRepository->findBy(['categoria' => $categoria]) as $jugadorCategoria) {
            $entityManager->remove($jugadorCategoria);
    }

        foreach ($idsJugadoresSeleccionados as $idJugador){
            $nuevoJugadorCategoria = new JugadorCategoria();
            $nuevoJugadorCategoria->setCategoria($categoria);
            $nuevoJugadorCategoria->setJugador($jugadorRepository->find($idJugador));
            $entityManager->persist($nuevoJugadorCategoria);
        }

        $entityManager->flush();
        $this->addFlash('success', 'Categoria modificada correctamente');
        return $this->redirectToRoute('admin_categoria_index');
    }

    #[Route('/{id}/eliminar', name: 'admin_categoria_delete', methods: ['POST'])]
    public function delete(Request $request,
                           Categoria $categoria,
                           EntityManagerInterface $entityManager,
                           JugadorCategoriaRepository $jugadorCategoriaRepository,
                           CategoriaRepository $categoriaRepository): Response
    {
        if($categoriaRepository->existeRankingPersonalDeCategoria($categoria->getId())){
            $this->addFlash('error', 'No se puede eliminar la categoría. Ya existen rankings con esta categoria');
            return $this->redirectToRoute('admin_categoria_index');
        }

        foreach ($jugadorCategoriaRepository->findBy(['categoria' => $categoria]) as $jugadorCategoria) {
            $entityManager->remove($jugadorCategoria);
        }

        $entityManager->remove($categoria);
        $entityManager->flush();

        $this->addFlash('success', 'Categoría eliminada correctamente.');

        return $this->redirectToRoute('admin_categoria_index');
    }
}

<?php

namespace App\Controller\Admin;

use App\Controller\RankingPersonalController;
use App\Entity\Categoria;
use App\Entity\JugadorCategoria;
use App\Entity\RankingGeneral;
use App\Repository\CategoriaRepository;
use App\Repository\JugadorCategoriaRepository;
use App\Repository\JugadorRepository;
use App\Repository\RankingGeneralRepository;
use App\Repository\RankingPersonalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminRankingGeneralController extends AbstractController
{
    #[Route('/admin/ranking/general', name: 'admin_ranking_general_index')]
    public function index(RankingGeneralRepository $rankingGeneralRepository, CategoriaRepository $categoriaRepository): Response
    {

        $datosRankings = $rankingGeneralRepository->obtenerDatosRankingGenerales();

        $datosCategorias = $categoriaRepository->findAll();

        return $this->render('admin/admin_ranking_general/index.html.twig', [
            'rankings' => $datosRankings,
            'categorias' => $datosCategorias,
        ]);
    }


    #[Route('/nueva', name: 'admin_ranking_new', methods: ['POST'])]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        RankingGeneralRepository $rankingGeneralRepository,
                        CategoriaRepository $categoriaRepository): Response
    {
        $titulo = $request->request->get('titulo');
        $descripcion = $request->request->get('descripcion');
        $titulo = $request->request->get('titulo');
        $idCategoria = $request->request->get('categoria');
        $esActivo = $request->request->get('activo') == '1';

        $rankingGeneral = new RankingGeneral();

        $rankingGeneral->setTitulo($titulo);
        $rankingGeneral->setDescripcion($descripcion);
        $rankingGeneral->setCategoria($categoriaRepository->find($idCategoria));
        $rankingGeneral->setActivo($esActivo);

        $entityManager->persist($rankingGeneral);
        $entityManager->flush();
        $this->addFlash('success', 'Ranking general creado correctamente');
        return $this->redirectToRoute('admin_ranking_general_index');
    }

    #[Route('/{id}/editar', name: 'admin_ranking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RankingGeneral $rankingGeneral,
                         EntityManagerInterface $entityManager,
                         CategoriaRepository $categoriaRepository): Response
    {
        $titulo = $request->request->get('titulo');
        $descripcion = $request->request->get('descripcion');
        $titulo = $request->request->get('titulo');
        $idCategoria = $request->request->get('categoria');
        $esActivo = $request->request->get('activo') == '1';

        $rankingGeneral->setTitulo($titulo);
        $rankingGeneral->setDescripcion($descripcion);
        $rankingGeneral->setCategoria($categoriaRepository->find($idCategoria));
        $rankingGeneral->setActivo($esActivo);

        $entityManager->persist($rankingGeneral);
        $entityManager->flush();
        $this->addFlash('success', 'Ranking general modificado correctamente');

        return $this->redirectToRoute('admin_ranking_general_index');
    }

    #[Route('/{id}/eliminar', name: 'admin_ranking_delete', methods: ['POST'])]
    public function delete(Request $request,
                           RankingGeneral $rankingGeneral,
                           EntityManagerInterface $entityManager,
                           RankingPersonalRepository $rankingPersonalRepository): Response
    {
        if($rankingPersonalRepository->findOneBy(['rankingGeneral' => $rankingGeneral])){
            $this->addFlash('error', 'No se puede eliminar el ranking general. Existen ranking personales asociados');
            return $this->redirectToRoute('admin_ranking_general_index');
        }

        $entityManager->remove($rankingGeneral);
        $entityManager->flush();
        $this->addFlash('success', 'Ranking general eliminado correctamente');
        return $this->redirectToRoute('admin_ranking_general_index');
    }
}

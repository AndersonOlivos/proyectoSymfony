<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categorias')]
final class AdminCategoriasController extends AbstractController
{
    #[Route('/', name: 'admin_categoria_index', methods: ['GET'])]
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        return $this->render('admin/admin_categorias/index.html.twig', [
            'categorias' => $categoriaRepository->findAll(),
        ]);
    }

    #[Route('/nueva', name: 'admin_categoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoria = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoria);
            $entityManager->flush();

            $this->addFlash('success', 'Categoría creada correctamente.');
            return $this->redirectToRoute('admin_categoria_index');
        }

        return $this->render('admin/categoria/form.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
            'titulo' => 'Nueva Categoría'
        ]);
    }

    #[Route('/{id}/editar', name: 'admin_categoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categoria, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Categoría actualizada correctamente.');
            return $this->redirectToRoute('admin_categoria_index');
        }

        return $this->render('admin/categoria/form.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
            'titulo' => 'Editar Categoría'
        ]);
    }

    #[Route('/{id}/eliminar', name: 'admin_categoria_delete', methods: ['POST'])]
    public function delete(Request $request, Categoria $categoria, EntityManagerInterface $entityManager): Response
    {
        // Token CSRF para seguridad (evita borrados accidentales por link)
        if ($this->isCsrfTokenValid('delete'.$categoria->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoria);
            $entityManager->flush();
            $this->addFlash('success', 'Categoría eliminada.');
        }

        return $this->redirectToRoute('admin_categoria_index');
    }
}

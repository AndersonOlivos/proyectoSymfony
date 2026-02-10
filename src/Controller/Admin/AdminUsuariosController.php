<?php

namespace App\Controller\Admin;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminUsuariosController extends AbstractController
{
    #[Route('/admin/usuarios', name: 'admin_usuarios_index')]
    public function index(UsuarioRepository $usuarioRepository): Response
    {

        $datosUsuarios = $usuarioRepository->obtenerDatosUsuarios();

        return $this->render('admin/admin_usuarios/index.html.twig', [
            'usuarios' => $datosUsuarios,
        ]);
    }
}

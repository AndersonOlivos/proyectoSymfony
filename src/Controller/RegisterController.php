<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        if($request->isMethod('POST')) {

            $nuevo_usuario = new Usuario();

            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('plainPassword');

            $nuevo_usuario->setUsername($username);
            $nuevo_usuario->setEmail($email);
            $nuevo_usuario->setRol(['ROLE_USER']);

            $hashedPassword = $userPasswordHasher->hashPassword(
                $nuevo_usuario,
                $password
            );

            $nuevo_usuario->setPassword($hashedPassword);

            $entityManager->persist($nuevo_usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_app');
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'registerController',
        ]);
    }
}

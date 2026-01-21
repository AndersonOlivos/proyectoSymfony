<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReeReviewController extends AbstractController
{
    #[Route('/ree/review', name: 'app_ree_review')]
    public function index(): Response
    {
        return $this->render('ree_review/index.html.twig', [
            'controller_name' => 'ReeReviewController',
        ]);
    }
}

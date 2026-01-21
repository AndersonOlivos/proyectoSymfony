<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RankingParticiparController extends AbstractController
{
    #[Route('/ranking/participar', name: 'app_ranking_participar')]
    public function index(): Response
    {
        return $this->render('ranking_participar/index.html.twig', [
            'controller_name' => 'RankingParticiparController',
        ]);
    }
}

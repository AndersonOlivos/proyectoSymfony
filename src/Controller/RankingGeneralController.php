<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RankingGeneralController extends AbstractController
{
    #[Route('/ranking/general', name: 'app_ranking_general')]
    public function index(): Response
    {
        return $this->render('ranking_general/index.html.twig', [
            'controller_name' => 'RankingGeneralController',
        ]);
    }
}

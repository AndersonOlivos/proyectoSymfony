<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RankingPersonalController extends AbstractController
{
    #[Route('/ranking/personal', name: 'app_ranking_personal')]
    public function index(): Response
    {
        return $this->render('ranking_personal/index.html.twig', [
            'controller_name' => 'RankingPersonalController',
        ]);
    }
}

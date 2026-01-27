<?php

namespace App\Controller;

use App\Repository\RankingGeneralRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RankingGeneralController extends AbstractController
{
    #[Route('/ranking/general', name: 'app_ranking_general')]
    public function index(RankingGeneralRepository $rankingGeneralRepository): Response
    {

        $rankingsGenerales = $rankingGeneralRepository->obtenerRankingGenerales();

        return $this->render('ranking_general/index.html.twig', [
            'rankings_generales' => $rankingsGenerales,
        ]);
    }
}

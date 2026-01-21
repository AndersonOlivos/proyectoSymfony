<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrdenRankingPersonalController extends AbstractController
{
    #[Route('/orden/ranking/personal', name: 'app_orden_ranking_personal')]
    public function index(): Response
    {
        return $this->render('orden_ranking_personal/index.html.twig', [
            'controller_name' => 'OrdenRankingPersonalController',
        ]);
    }
}

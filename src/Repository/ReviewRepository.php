<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function obtenerReviewJugador(int $idJugador, int $idUsuario): ?Review
    {
        return $this->findOneBy([
            'jugador' => $idJugador,
            'usuario' => $idUsuario,
            'activo'  => true
        ]);
    }

    public function obtenerAllReviewsJugador(int $idJugador) : ?array{
        return $this->findBy(
            [
                'jugador' => $idJugador,
                'activo'  => true
            ],
            ['id' => 'DESC']
        );
    }

    public function obtenerMediaReviews(int $idJugador): float
    {
        $media = $this->createQueryBuilder('r')
            ->select('AVG(r.puntuacion) as media')
            ->andWhere('r.jugador = :valJugador')
            ->andWhere('r.activo = :valActivo')
            ->setParameter('valJugador', $idJugador)
            ->setParameter('valActivo', true)
            ->getQuery()
            ->getSingleScalarResult();

        return $media ? (float) round($media, 1) : 0.0;
    }
}

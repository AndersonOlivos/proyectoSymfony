<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
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

    public function valoracionesTotales(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select count(*) as numeroValoracionesTotales from review r';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAssociative();
    }

    public function jugadoresMejorValoracion(int $limit){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select j.nombre,
                j.imagen_url as imagenUrl,
                j.puntos,
                count(r.id) as numVotos,
                round(sum(r.puntuacion)/count(r.id),2) as notaMedia
                from review r join jugador j on r.id_jugador = j.id
                group by j.nombre, j.imagen_url, j.puntos order by notaMedia desc limit :limit';
        $resultSet = $conn->executeQuery($sql,['limit'=>$limit],['limit'=>ParameterType::INTEGER]);
        return $resultSet->fetchAllAssociative();
    }

    public function resumenRapido(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select (select count( distinct u.id) from usuario u
                join review r on r.id_usuario = u.id)
                as usuariosHanVotado,
                (select round(sum(r.puntuacion)/count(r.id),1) from review r)
                as mediaGlobalNotas';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAssociative();
    }
}

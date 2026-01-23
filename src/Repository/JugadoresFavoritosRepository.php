<?php

namespace App\Repository;

use App\Entity\Jugador;
use App\Entity\JugadoresFavoritos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JugadoresFavoritos>
 */
class JugadoresFavoritosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JugadoresFavoritos::class);
    }

    public function obtenerJugadoresFavoritosActivos(int $idUsuario): array{

        return $this->getEntityManager()->createQueryBuilder()
            ->select('j')
            ->from(Jugador::class, 'j')
            ->join(JugadoresFavoritos::class, 'jf', 'WITH', 'jf.jugador = j')
            ->where('jf.usuario = :valUsuario')
            ->andWhere('j.activo = :valActivo')
            ->andWhere('jf.favorito = :valFavorito')
            ->setParameter('valUsuario', $idUsuario)
            ->setParameter('valActivo', true)
            ->setParameter('valFavorito', true)
            ->getQuery()
            ->getResult();
    }
}

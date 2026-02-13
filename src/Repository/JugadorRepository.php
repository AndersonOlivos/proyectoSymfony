<?php

namespace App\Repository;

use App\Entity\Jugador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jugador>
 */
class JugadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jugador::class);
    }

    public function buscarConFiltros(?string $busqueda, int $pagina, int $limite = 10): array
    {
        $query = $this->createQueryBuilder('j')
            ->orderBy('j.nombre', 'ASC');

        if ($busqueda) {
            $query->andWhere('j.nombre LIKE :val OR j.email LIKE :val')
                ->setParameter('val', '%' . $busqueda . '%');
        }

        $totalQuery = clone $query;
        $total = count($totalQuery->select('j.id')->getQuery()->getResult());
        $maxPaginas = ceil($total / $limite);

        $query->setFirstResult(($pagina - 1) * $limite)
            ->setMaxResults($limite);

        return [
            'datos' => $query->getQuery()->getResult(),
            'total' => $total,
            'maxPaginas' => $maxPaginas,
            'paginaActual' => $pagina
        ];
    }

    public function obtenerDatosJugadoresCategoria(){

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            select j.id, j.nombre, j.puntos
            from jugador j
            where j.activo = true
            order by j.puntos desc;
            ';

        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    public function noPuedeEliminarse(int $idJugador){
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            select ((select (count(jf.id) > 0) as existe_favorito
            from jugadores_favoritos jf
            where jf.id = :idJugador)
            or (
            select (count(orp.id_jugador) > 0) as existe_ranking
            from orden_ranking_personal orp
            where orp.id_jugador = :idJugador
            )
            or (
            select (count(r.id_jugador) >0) as existe_valoracion
            from review r
            where r.id_jugador = :idJugador
            )) as existe';
        $resultSet = $conn->executeQuery($sql, ['idJugador' => $idJugador]);
        return $resultSet->fetchAssociative();
    }

    public function numeroJugadoresRegistrados(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select count(*) as numeroJugadoresRegistrados from jugador j';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAssociative();
    }

    public function nombreJugadorMasVotado(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select j.nombre, count(j.nombre) as vecesVotado from review r
        join jugador j on r.id_jugador = j.id
        group by j.nombre order by vecesVotado desc limit 1';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAssociative();
    }

}

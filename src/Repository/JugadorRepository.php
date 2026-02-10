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

}

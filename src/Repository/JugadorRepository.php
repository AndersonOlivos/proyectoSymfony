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

        // Si hay búsqueda, filtramos
        if ($busqueda) {
            $query->andWhere('j.nombre LIKE :val OR j.email LIKE :val')
                ->setParameter('val', '%' . $busqueda . '%');
        }

        // Clonamos para contar el total antes de recortar (para la paginación)
        $totalQuery = clone $query;
        $total = count($totalQuery->select('j.id')->getQuery()->getResult());
        $maxPaginas = ceil($total / $limite);

        // Aplicamos la paginación (Offset y Limit)
        $query->setFirstResult(($pagina - 1) * $limite)
            ->setMaxResults($limite);

        return [
            'datos' => $query->getQuery()->getResult(),
            'total' => $total,
            'maxPaginas' => $maxPaginas,
            'paginaActual' => $pagina
        ];
    }
}

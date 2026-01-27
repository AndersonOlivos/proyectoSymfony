<?php

namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categoria>
 */
class CategoriaRepository extends ServiceEntityRepository
{

    private $jugadorCategoriaRepository;

    public function __construct(ManagerRegistry $registry, JugadorCategoriaRepository $jugadorCategoriaRepository)
    {
        parent::__construct($registry, Categoria::class);
        $this->jugadorCategoriaRepository = $jugadorCategoriaRepository;
    }

    public function obtenerCategoriasJugador(int $id){
        $idCategorias = $this->jugadorCategoriaRepository->obtenerIdCategoriasJugador($id);
        $categorias = [];
        if(!empty($idCategorias)){
            foreach ($idCategorias as $idCategoria){
                $categorias[] = $this->findOneBy([
                    'id' => $idCategoria,
                    'activo' => true
                ]);
            }
        }
        return $categorias;
    }

    public function obtenerJugadoresPorCategoria(int $idCategoria){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            select
            j.id,
            j.nombre,
            j.imagen_url
            from
                jugador j,
                jugador_categoria jc
            where
                j.id = jc.id_jugador
                and j.activo = 1
                and jc.id_categoria = :idCategoria;
                ';

        $resultSet = $conn->executeQuery($sql, ['idCategoria' => $idCategoria]);
        return $resultSet->fetchAllAssociative();
    }
}

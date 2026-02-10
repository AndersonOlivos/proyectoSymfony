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

    public function obtenerDatosCategorias(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                c.id,
                c.nombre,
                c.activo,
                COUNT(jc.id_jugador) as numero_jugadores,
                GROUP_CONCAT(jc.id_jugador) as ids_jugadores
            FROM categoria c
            LEFT JOIN jugador_categoria jc ON c.id = jc.id_categoria
            GROUP BY c.id, c.nombre, c.activo;
            ';

        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    public function existeRankingPersonalDeCategoria($categoria){
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        select (count(c.id) > 0) as existe from categoria c
        left join ranking_general rg on c.id = rg.id_categoria
        left join ranking_personal rp on rg.id = rp.id_ranking_general
        where c.id = :idCategoria';

        $resultSet = $conn->executeQuery($sql, ['idCategoria' => $categoria]);
        return $resultSet->fetchAssociative();
    }
}

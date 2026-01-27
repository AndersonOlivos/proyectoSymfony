<?php

namespace App\Repository;

use App\Entity\RankingGeneral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RankingGeneral>
 */
class RankingGeneralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankingGeneral::class);
    }

    public function obtenerRankingGeneralesJugador($categorias){

        $rankingGenerales = [];

        foreach($categorias as $categoria){
            $rankingGenerales[] = $this -> findOneBy(['categoria' => $categoria->getId(), 'activo' => true]);
        }

        if(count($rankingGenerales) > 0 && $rankingGenerales[0] != null){
            return $rankingGenerales;
        } else return [];
    }

    public function obtenerRankingGenerales(){

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT
                rg.id AS id_ranking,
                rg.titulo AS titulo_ranking,
                rg.descripcion AS descripcion_ranking,
                (SELECT
                    c.nombre
                FROM categoria c
                WHERE c.id = rg.id_categoria
                ) AS nombre_categoria,
                (
                SELECT
                    COUNT(*)
                FROM
                    ranking_personal rp
                WHERE
                    rp.id_ranking_general = rg.id
                    AND rp.activo = 1
                ) AS total_participantes,
                (
                SELECT
                    COUNT(*)
                FROM
                    ranking_personal rp
                WHERE
                    rp.id_ranking_general = rg.id
                    AND rp.id_usuario = 1
                    AND rp.activo = 1
                ) AS usuario_participa
                FROM
                    ranking_general rg
                WHERE
                    rg.activo = 1
                ORDER BY
                    rg.id DESC ';

        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    public function obtenerRankingGeneral(int $id_ranking){

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT
                rg.id AS id_ranking,
                rg.titulo AS titulo_ranking,
                rg.descripcion AS descripcion_ranking,
                (SELECT
                     c.id
                FROM categoria c
                WHERE c.id = rg.id_categoria
                ) AS id_categoria,
                (SELECT
                    c.nombre
                FROM categoria c
                WHERE c.id = rg.id_categoria
                ) AS nombre_categoria,
                (
                SELECT
                    COUNT(*)
                FROM
                    ranking_personal rp
                WHERE
                    rp.id_ranking_general = rg.id
                    AND rp.activo = 1
                ) AS total_participantes,
                (
                SELECT
                    COUNT(*)
                FROM
                    ranking_personal rp
                WHERE
                    rp.id_ranking_general = rg.id
                    AND rp.id_usuario = 1
                    AND rp.activo = 1
                ) AS usuario_participa
                FROM
                    ranking_general rg
                WHERE
                    rg.activo = 1
                AND
                    rg.id = :id_ranking
                ORDER BY
                    rg.id DESC ';

        $resultSet = $conn->executeQuery($sql, ['id_ranking' => $id_ranking]);
        return $resultSet->fetchAllAssociative();
    }
}

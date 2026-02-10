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

        if($categorias != null && $categorias->count() > 0 ){
            foreach($categorias as $categoria){
                $rankingGenerales[] = $this -> findOneBy(['categoria' => $categoria->getId(), 'activo' => true]);
            }
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

    public function obtenerTopRankingGeneralPosicion(int $idRankingGeneral, int $posicion){

        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        select orp.posicion, j.nombre,count(*) * 100.0 / (
        select count(*) from orden_ranking_personal orp , ranking_personal rp , ranking_general rg
        where rg.id = rp.id_ranking_general
        and orp.id_ranking_personal = rp.id
        and rg.id = :idRankingGeneral
        and orp.posicion = :posicion
        ) as veces_votado from orden_ranking_personal orp , ranking_personal rp , ranking_general rg, jugador j
        where rg.id = rp.id_ranking_general
        and orp.id_ranking_personal = rp.id
        and j.id = orp.id_jugador
        and rg.id = :idRankingGeneral
        and orp.posicion = :posicion
        group by orp.id_jugador, orp.posicion, j.nombre
        order by veces_votado desc limit 1;
        ';

        $resultSet = $conn->executeQuery($sql, ['idRankingGeneral' => $idRankingGeneral, 'posicion' => $posicion]);
        return $resultSet->fetchAssociative();
    }

    public function obtenerTopXRankingGeneral(int $idRankingGeneral, int $top){

        $topx = [];

        for ($i = 1; $i <= $top; $i++) {
            $resultado = $this->obtenerTopRankingGeneralPosicion($idRankingGeneral, $i);

            if ($resultado !== false) {
                $topx[] = $resultado;
            }        }

        return $topx;
    }
}

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

    public function obtenerRankingGeneralesJugador(int $idJugador){

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select rg.id, rg.titulo, rg.descripcion
            from ranking_general rg
            join categoria c on rg.id_categoria = c.id
            join jugador_categoria jc on jc.id_categoria = c.id
            where jc.id_jugador = :idJugador';

        $resultSet = $conn->executeQuery($sql, ['idJugador' => $idJugador]);
        return $resultSet->fetchAllAssociative();
    }

    public function obtenerRankingGenerales(int $idUsuario){

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
                    AND rp.id_usuario = :idUsuario
                    AND rp.activo = 1
                ) AS usuario_participa
                FROM
                    ranking_general rg
                WHERE
                    rg.activo = 1
                ORDER BY
                    rg.id DESC ';

        $resultSet = $conn->executeQuery($sql, ['idUsuario' => $idUsuario]);
        return $resultSet->fetchAllAssociative();
    }

    public function obtenerRankingGeneral(int $id_ranking, int $id_usuario){

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
                    AND rp.id_usuario = :id_usuario
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

        $resultSet = $conn->executeQuery($sql, ['id_ranking' => $id_ranking, 'id_usuario' => $id_usuario]);
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

    public function obtenerDatosRankingGenerales(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            select rg.id, rg.titulo, rg.descripcion, rg.activo, c.id as categoria_id, c.nombre as categoria_nombre
            from ranking_general rg
            join categoria c on rg.id_categoria = c.id;
            ';

        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    public function numeroRankingsActivos(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        select count(*) as numeroRankingsActivos from ranking_general rg where rg.activo = 1;
        ';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAssociative();
    }
    public function rankingsMasActivos(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select rg.titulo,c.nombre,count(rp.id) as votos from ranking_general rg
                join ranking_personal rp on rg.id = rp.id_ranking_general
                join categoria c on c.id = rg.id_categoria
                group by rg.titulo, c.nombre order by votos desc';
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }
}

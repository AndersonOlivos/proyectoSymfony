<?php

namespace App\Repository;

use App\Entity\OrdenRankingPersonal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrdenRankingPersonal>
 */
class OrdenRankingPersonalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdenRankingPersonal::class);
    }

    public function obtenerOrdenRankingPersonal($id_usuario, $id_ranking_general){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                select
                id_jugador,
                posicion
                from
                    orden_ranking_personal orp ,
                    ranking_personal rp,
                    ranking_general rg
                where
                    orp.id_ranking_personal = rp.id
                    and rp.id_usuario = :id_usuario
                    and rp.id_ranking_general = (
                    SELECT
                        id
                    from
                        ranking_general rg2
                    where
                        rg2.id = :id_ranking_general)
                    and rp.activo = true
                        ';

        $resultSet = $conn->executeQuery($sql, ['id_ranking_general' => $id_ranking_general, 'id_usuario' => $id_usuario]);
        return $resultSet->fetchAllAssociative();
    }
}

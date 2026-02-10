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
                select orp.id_jugador, orp.posicion from orden_ranking_personal orp
                join ranking_personal rp on orp.id_ranking_personal = rp.id
                where rp.id_usuario = :idUsuario and rp.id_ranking_general = :idRankingGeneral
                and rp.activo = true';

        $resultSet = $conn->executeQuery($sql, ['idRankingGeneral' => $id_ranking_general, 'idUsuario' => $id_usuario]);
        return $resultSet->fetchAllAssociative();
    }
}

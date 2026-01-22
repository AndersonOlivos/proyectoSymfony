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

    //    /**
    //     * @return RankingGeneral[] Returns an array of RankingGeneral objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RankingGeneral
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

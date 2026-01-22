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

    //    /**
    //     * @return Categoria[] Returns an array of Categoria objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categoria
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

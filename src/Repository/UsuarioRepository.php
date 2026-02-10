<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }


    public function obtenerDatosUsuarios(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            select u.username, u.email, u.rol, u.activo, count(r.id) reviews_hechas
            from usuario u
            join review r on r.id_usuario = u.id
            group by u.username, u.email, u.rol, u.activo;
            ';

        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }
}

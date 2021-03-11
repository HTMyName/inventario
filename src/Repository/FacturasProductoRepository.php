<?php

namespace App\Repository;

use App\Entity\FacturasProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacturasProducto|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacturasProducto|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacturasProducto[]    findAll()
 * @method FacturasProducto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturasProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacturasProducto::class);
    }

    // /**
    //  * @return FacturasProducto[] Returns an array of FacturasProducto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FacturasProducto
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

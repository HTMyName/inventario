<?php

namespace App\Repository;

use App\Entity\FacturasServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacturasServicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacturasServicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacturasServicio[]    findAll()
 * @method FacturasServicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturasServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacturasServicio::class);
    }

    // /**
    //  * @return FacturasServicio[] Returns an array of FacturasServicio objects
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
    public function findOneBySomeField($value): ?FacturasServicio
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

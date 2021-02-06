<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Producto::class);
	}

	public function deleteBy($id)
	{
		return $this->createQueryBuilder('p')
			->update('App:Producto', 'p')
			->set('p.active', '0')
			->where("p.id = :id")
			->setParameter('id', $id)
			->getQuery()
			->getResult();
	}

	public function showAllProducts()
	{
		return $this->createQueryBuilder('p')
			->select('p.id', 'p.marca', 'p.modelo', 'p.serie', 'p.precioC', 'p.precioV', 'p.ganancia')
			->where('p.active = 1')
			->getQuery()
			->getResult();
	}

	public function showAllIventario()
	{
		return $this->createQueryBuilder('p')
			->select('p.id', 'p.marca', 'p.modelo', 'p.serie', 'p.precioC', 'p.precioV', 'p.ganancia', 'p.cantidad_inventario')
			->where('p.active = 1')
			->andWhere('p.cantidad_inventario > 0')
			->getQuery()
			->getResult();
	}

	public function showAllTaller()
	{
		return $this->createQueryBuilder('p')
			->select('p.id', 'p.marca', 'p.modelo', 'p.serie', 'p.precioC', 'p.precioV', 'p.ganancia', 'p.cantidad_taller')
			->where('p.active = 1')
			->andWhere('p.cantidad_taller > 0')
			->getQuery()
			->getResult();
	}

	public function showTallerBy($data)
	{
		return $this->createQueryBuilder('p')
			->select('p.id', 'p.marca', 'p.modelo','p.precioV')
			->where('p.active = 1')
			->andWhere('p.cantidad_taller > 0')
			->andWhere('p.marca like :data')
			->orWhere('p.modelo like :data')
			->orWhere('p.precioV like :data')
			->setParameter('data', "%$data%")
			->setMaxResults(5)
			->getQuery()
			->getResult();
	}

	// /**
	//  * @return Producto[] Returns an array of Producto objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Producto
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}

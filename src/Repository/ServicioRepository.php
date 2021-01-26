<?php

namespace App\Repository;

use App\Entity\Servicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Servicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Servicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Servicio[]    findAll()
 * @method Servicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicioRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Servicio::class);
	}

	public function findAllClients()
	{
		return $this->createQueryBuilder('s')
			->select('s')
			->where('s.active = 1')
			->getQuery()
			->getResult();
	}

	public function deleteBy($id)
	{
		return $this->createQueryBuilder('s')
			->update(Servicio::class, 's')
			->set('s.active', '0')
			->where('s.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getResult();
	}

	// /**
	//  * @return Servicio[] Returns an array of Servicio objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('s')
			->andWhere('s.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('s.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Servicio
	{
		return $this->createQueryBuilder('s')
			->andWhere('s.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}

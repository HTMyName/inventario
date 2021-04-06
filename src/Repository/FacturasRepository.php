<?php

namespace App\Repository;

use App\Entity\Facturas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facturas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facturas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facturas[]    findAll()
 * @method Facturas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturasRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Facturas::class);
	}

	public function getUserFacturas($id_user)
	{

		$mes = $this->getMes();

		return $this->createQueryBuilder('f')
			->select('f', 'fp', 'fs')
			->where('f.id_user = :id_user')
			->setParameter('id_user', $id_user)
			->andWhere('f.fecha >= :fecha')
			->setParameter('fecha', $mes)
			//->andWhere('f.xpagar != 0')
			->orWhere('f.xpagar > 0')
			->leftJoin('f.productos', 'fp')
			->leftJoin('f.servicios', 'fs')
			->orderBy('f.id', 'DESC')
			->getQuery()
			->getResult();
	}

	private function getMes()
	{
		$currentMonthDateTime = new \DateTime();
		$firstDateTime = $currentMonthDateTime->modify('first day of this month');
		$firstDateTime->setTime(0, 0);
		return $firstDateTime;
	}

	public function getAllUserFacturas($id_user)
	{

		$year = $this->getYear();

		return $this->createQueryBuilder('f')
			->select('f', 'fp', 'fs')
			->where('f.id_user = :id_user')
			->setParameter('id_user', $id_user)
			->andWhere('f.fecha >= :fecha')
			->setParameter('fecha', $year)
			->orWhere('f.xpagar > 0')
			->leftJoin('f.productos', 'fp')
			->leftJoin('f.servicios', 'fs')
			->orderBy('f.id', 'DESC')
			->getQuery()
			->getResult();
	}

	private function getYear()
	{
		$year = date('Y');
		$currentMonthDateTime = new \DateTime();
		$firstDateTime = $currentMonthDateTime->modify('now');
		$firstDateTime->setTime(0, 0);
		$firstDateTime->setDate($year, 1, 1);
		return $firstDateTime;
	}

	public function getFacturasXcobrar()
	{

		$mes = $this->getMes();

		return $this->createQueryBuilder('f')
			->select('f', 'fp', 'fs')
			->andWhere('f.fecha >= :fecha')
			->setParameter('fecha', $mes)
			->andWhere('f.xpagar != 0')
			->orWhere('f.xpagar > 0')
			->leftJoin('f.productos', 'fp')
			->leftJoin('f.servicios', 'fs')
			->orderBy('f.id', 'DESC')
			->getQuery()
			->getResult();
	}

	public function getFacturaById($id)
	{
		return $this->createQueryBuilder('f')
			->select('f', 'fp', 'p', 'fs', 's')
			->where('f.id = :id')
			->setParameter('id', $id)
			->leftJoin('f.productos', 'fp')
			->leftJoin('fp.id_producto', 'p')
			->leftJoin('f.servicios', 'fs')
			->leftJoin('fs.id_servicio', 's')
			->getQuery()
			->getResult();
	}

	public function getSumXpagar()
	{
		return $this->createQueryBuilder('f')
			->select('SUM(f.xpagar) as xpagar')
			->getQuery()
			->getResult();
	}

	/*public function getUserFacturas($id_user){
		return $this->createQueryBuilder('f')
			->select('f', 'fp', 'p', 'fs', 's')
			->where('f.id_user = :id_user')
			->setParameter('id_user', $id_user)
			->innerJoin('f.productos', 'fp')
			->innerJoin('fp.id_producto', 'p')
			->innerJoin('f.servicios', 'fs')
			->innerJoin('fs.id_servicio', 's')
			->getQuery()
			->getResult()
			;
	}*/

	// /**
	//  * @return Facturas[] Returns an array of Facturas objects
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
	public function findOneBySomeField($value): ?Facturas
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

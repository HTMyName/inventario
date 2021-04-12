<?php

namespace App\Repository;

use App\Entity\Logs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Logs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logs[]    findAll()
 * @method Logs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogsRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Logs::class);
	}

	public function getClientPays($idfactura)
	{
		return $this->createQueryBuilder('l')
			->select('l')
			->where('l.tipo = :tipo')
			->setParameter('tipo', 'pago')
			->andWhere('l.id_factura = :idfactura')
			->setParameter('idfactura', $idfactura)
			->orderBy('l.id', 'DESC')
			->getQuery()
			->getResult();
	}

	public function getLogs($year, $mes, $tipo, $user)
	{

		$qb = $this->createQueryBuilder('l');
		$qb->select('l');
		if ($mes == "all") {
			$fecha = $this->getYearFirstLastDay($year);
			$qb->where('l.fecha >= :fecha_ini')->setParameter('fecha_ini', $fecha[0]);
			$qb->andWhere('l.fecha <= :fecha_fin')->setParameter('fecha_fin', $fecha[1]);
		} else {
			$fecha = $this->getMesFirstLastDay($year, $mes);
			$qb->where('l.fecha >= :fecha_ini')->setParameter('fecha_ini', $fecha[0]);
			$qb->andWhere('l.fecha <= :fecha_fin')->setParameter('fecha_fin', $fecha[1]);
		}
		if ($user != "all") {
			$qb->andWhere('l.id_user = :id_user')->setParameter('id_user', $user);
		}
		if ($tipo != "all") {
			$qb->andWhere('l.tipo = :tipo')->setParameter('tipo', $tipo);
		}
		$qb->orderBy('l.id', 'DESC');

		return $qb->getQuery()->getResult();
	}

	public function getBajas()
	{
		$mes = $this->getMes();

		return $this->createQueryBuilder('l')
			->select('l.detalles')
			->where('l.tipo = :tipo')
			->andWhere('l.fecha >= :fecha')
			->setParameter('tipo', 'baja')
			->setParameter('fecha', $mes)
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

	private function getMesFirstLastDay($year, $mes)
	{
		$array = [];

		$currentMonthDateTime = new \DateTime("{$year}-{$mes}-1");
		$firstDateTime = $currentMonthDateTime->modify('first day of this month');
		$firstDateTime->setTime(0, 0);

		$currentMonthDateTime2 = new \DateTime("{$year}-{$mes}-1");
		$lastDateTime = $currentMonthDateTime2->modify('last day of this month');
		$lastDateTime->setTime(23, 59, 59);

		$array [] = $firstDateTime;
		$array [] = $lastDateTime;

		return $array;
	}

	private function getYearFirstLastDay($year)
	{
		$array = [];

		$currentMonthDateTime = new \DateTime("{$year}-1-1");
		$firstDateTime = $currentMonthDateTime->modify('first day of this month');
		$firstDateTime->setTime(0, 0);

		$currentMonthDateTime2 = new \DateTime("{$year}-12-1");
		$lastDateTime = $currentMonthDateTime2->modify('last day of this month');
		$lastDateTime->setTime(23, 59, 59);

		$array [] = $firstDateTime;
		$array [] = $lastDateTime;

		return $array;
	}

	// /**
	//  * @return Logs[] Returns an array of Logs objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('l')
			->andWhere('l.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('l.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Logs
	{
		return $this->createQueryBuilder('l')
			->andWhere('l.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}

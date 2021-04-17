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

		$qb = $this->createQueryBuilder('f')
			->select('f', 'fp', 'fs');
		if ($id_user->getRoles()[0] == "ROLE_ADMIN") {
			$qb->where('f.fecha >= :fecha')
				->setParameter('fecha', $mes);
		} else {
			$qb->where('f.id_user = :id_user')
				->setParameter('id_user', $id_user->getId())
				->andWhere('f.fecha >= :fecha')
				->setParameter('fecha', $mes);
		}
		$qb->orWhere('f.xpagar > 0')
			->leftJoin('f.productos', 'fp')
			->leftJoin('f.servicios', 'fs')
			->orderBy('f.id', 'DESC');
		return $qb->getQuery()->getResult();
	}

	public function getMesFacturas($id_user)
	{
		$mes = $this->getMes();

		$qb = $this->createQueryBuilder('f');
		$qb->select('f', 'fp', 'fs');

		if ($id_user != null) {
			$qb->where('f.id_user = :id_user')->setParameter('id_user', $id_user);
			$qb->andWhere('f.fecha >= :fecha')->setParameter('fecha', $mes);
		} else {
			$qb->where('f.fecha >= :fecha')->setParameter('fecha', $mes);
		}
		$qb->leftJoin('f.productos', 'fp')
			->leftJoin('f.servicios', 'fs')
			->orderBy('f.id', 'DESC');
		return $qb->getQuery()->getResult();
	}

	public function getYearFacturas($id_user)
	{
		$year = $this->getYear();

		$qb = $this->createQueryBuilder('f');
		$qb->select('f', 'fp', 'fs');

		if ($id_user != null) {
			$qb->where('f.id_user = :id_user')->setParameter('id_user', $id_user);
			$qb->andWhere('f.fecha >= :fecha')->setParameter('fecha', $year);
		} else {
			$qb->where('f.fecha >= :fecha')->setParameter('fecha', $year);
		}
		$qb->leftJoin('f.productos', 'fp')
			->leftJoin('f.servicios', 'fs')
			->orderBy('f.id', 'DESC');
		return $qb->getQuery()->getResult();
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

	private function getMes()
	{
		$currentMonthDateTime = new \DateTime();
		$firstDateTime = $currentMonthDateTime->modify('first day of this month');
		$firstDateTime->setTime(0, 0);
		return $firstDateTime;
	}
}
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

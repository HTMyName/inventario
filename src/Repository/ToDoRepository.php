<?php

namespace App\Repository;

use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, ToDo::class);
	}

	// /**
	//  * @return ToDo[] Returns an array of ToDo objects
	//  */

	public function findAllTodo($user)
	{
		$mes = $this->getMes();

		$qb = $this->createQueryBuilder('t');
		$qb->where('t.fecha >= :fecha')->setParameter('fecha', $mes);

		if ($user != null) {
			$qb->andWhere('t.id_user = :iduser')->setParameter('iduser', $user);
			$qb->orWhere('t.visible = :visible')->setParameter('visible', 1);
		}

		//dump($qb->getQuery());

		return $qb->getQuery()->getResult();
	}

	private function getMes()
	{
		$currentMonthDateTime = new \DateTime();
		$firstDateTime = $currentMonthDateTime->modify('first day of this month');
		$firstDateTime->setTime(0, 0);
		return $firstDateTime;
	}


	/*
	public function findOneBySomeField($value): ?ToDo
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}

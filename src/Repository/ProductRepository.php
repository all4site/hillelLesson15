<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Product::class);
	}

	public function sortById()
	{
		return $this->createQueryBuilder('b')
				->orderBy('b.id', 'DESC');
	}

	public function querySearchForParameter(string $s = null)
	{
		$query = $this->createQueryBuilder('p');


		if ($s) {
			$query
					->andWhere(
							$query->expr()->like("p.name", ":name")
					)
					->setParameter(':name', '%' . mb_strtolower($s) . '%')
					->orWhere(
							$query->expr()->like("p.description", ":description")
					)
					->setParameter(':description', '%' . mb_strtolower($s) . '%');
		}


		return $query;
	}

	// /**
	//  * @return Product[] Returns an array of Product objects
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
	public function findOneBySomeField($value): ?Product
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

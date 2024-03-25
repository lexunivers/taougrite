<?php

namespace App\Repository;

use App\Entity\Avions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avions>
 *
 * @method Avions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avions[]    findAll()
 * @method Avions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avions::class);
    }

    public function myfindAvion($avion)
	{
		return $this->createQueryBuilder('a')
		->select('a.id','a.type', 'a.immatriculation')
		->where('a.id = :ResourcesId')
		->setParameter('ResourcesId',$avion)
		->getQuery()
        ->getResult()
		;
	}    
    //    /**
    //     * @return Avions[] Returns an array of Avions objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Avions
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

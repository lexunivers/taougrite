<?php

namespace App\Repository;

use App\Entity\Qualification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Qualification>
 *
 * @method Qualification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Qualification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Qualification[]    findAll()
 * @method Qualification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QualificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Qualification::class);
    }

    public function myfindQualifs($reservataire)
	{

        $qb = $this->createQueryBuilder('d');
         
        $qb->where('d.user = :reservataire')
           ->andWhere('d.name = :reservataire') 
           ->setParameter('reservataire', $reservataire);
        return $qb;
		
	}


//    /**
//     * @return Qualification[] Returns an array of Qualification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Qualification
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\ComptePilote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComptePilote>
 *
 * @method ComptePilote|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComptePilote|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComptePilote[]    findAll()
 * @method ComptePilote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComptePiloteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComptePilote::class);
    }

    //    /**
    //     * @return ComptePilote[] Returns an array of ComptePilote objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ComptePilote
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

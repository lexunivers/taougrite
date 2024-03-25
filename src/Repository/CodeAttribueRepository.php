<?php

namespace App\Repository;

use App\Entity\CodeAttribue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CodeAttribue>
 *
 * @method CodeAttribue|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeAttribue|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeAttribue[]    findAll()
 * @method CodeAttribue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeAttribueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeAttribue::class);
    }

    //    /**
    //     * @return CodeAttribue[] Returns an array of CodeAttribue objects
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

    //    public function findOneBySomeField($value): ?CodeAttribue
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

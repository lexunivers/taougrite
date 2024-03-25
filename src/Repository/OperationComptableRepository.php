<?php

namespace App\Repository;

use App\Entity\OperationComptable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OperationComptable>
 *
 * @method OperationComptable|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationComptable|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationComptable[]    findAll()
 * @method OperationComptable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationComptableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationComptable::class);
    }

    public function myfindSommeTotale($user)
    {
        return $this->createQueryBuilder('O')
            ->select('O.CompteId,O.OperSensMt,SUM(O.OperMontant)')
            ->Where('O.CompteId = :user')
            ->setParameter('user', $user)
            ->GROUPBY('O.OperSensMt')
            ->getQuery()
            ->getResult()
        ;
    }

    public function myFindDebit($user)
    {
        return $this->createQueryBuilder('O')
            ->select('SUM(O.OperMontant) ')
            ->Where('O.OperSensMt = 0 ')
            ->andWhere('O.CompteId = :CompteId')
            ->setParameter('CompteId', $user)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function myFindCredit($user)
    {
        return $this->createQueryBuilder('O')
            ->select('SUM(O.OperMontant) ')
            ->Where('O.OperSensMt = 1 ')
            ->andWhere('O.CompteId = :CompteId')
            ->setParameter('CompteId', $user)
            ->getQuery()
            ->getResult()
        ;
    } 

    // nécessaire pour statistiques charts.js
    public function findByRecetteCB() {
        return $this->createQueryBuilder('o')
            ->select('SUBSTRING(o.OperDate, 1, 10) as jour, SUM(o.OperMontant) as total, count(o) as nombre ')
            ->where('o.OperSensMt = 1')
            ->groupBy('jour')
            ->getQuery()
            ->getResult()
        ;        
    } 

    public function findByCptesDebiteurs() {
        return $this->createQueryBuilder('o')
            ->select('SUBSTRING(o.OperDate, 1, 10) as jour, SUM(o.OperMontant) as total, count(o) as nombre ')
            ->where('o.OperSensMt = 0')
            ->groupBy('jour')
            ->getQuery()
            ->getResult()
        ;        
    } 

    public function findBySommeTotaleRecette()
    {
        return $this->createQueryBuilder('o')
            ->select('SUM(o.OperMontant) as total')
            ->Where('o.OperSensMt = 0 ')
            //->GROUPBY('O.OperSensMt')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findBySommeTotaleCredit()
    {
        return $this->createQueryBuilder('o')
        ->select('SUM(o.OperMontant) as total')
        ->where('o.OperSensMt = 1')
        //->groupBy('jour')
            ->getQuery()
            ->getResult()
        ;
    } 

    //    /**
    //     * @return OperationComptable[] Returns an array of OperationComptable objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OperationComptable
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

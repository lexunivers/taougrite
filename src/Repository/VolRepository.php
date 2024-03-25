<?php

namespace App\Repository;

use App\Entity\Vol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vol>
 *
 * @method Vol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vol[]    findAll()
 * @method Vol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vol::class);
    }

    // données pour le carnet de vol
    // ---------------------------------------
    public function myDureeTotaleSolo($user){
        return $this->createQueryBuilder('v')    
        ->Select('SUM(v.heureArrivee - v.heureDepart) as SommeDureeSolo')
        ->where('v.typevol = 1') 
        ->andWhere('v.user =:user')
        ->setParameter('user',$user)
        ->getQuery()
        ->getSingleResult()
        ; 
    }
    
    
    public function myDureeTotaleDouble($user){
        return $this->createQueryBuilder('V')
    
        ->Select('SUM(V.heureArrivee - V.heureDepart )as SommeDureeDouble' )
        ->where('V.typevol = 2' )
        ->andWhere('V.user =:user')
        ->setParameter('user',$user) 
        ->getQuery()
        ->getSingleResult()    
        ;
    }    
    
    public function myDureeTotaleGlobal($user){
        return $this->createQueryBuilder('v')
    
        ->Select('SUM( (v.heureArrivee - v.heureDepart) ) as SommeDureeGlobale')
        ->where('v.user =:user')
        ->setParameter('user',$user)
        ->getQuery()
        ->getSingleResult()    
        ;
    }

    //    /**
    //     * @return Vol[] Returns an array of Vol objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vol
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

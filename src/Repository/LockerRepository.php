<?php

namespace App\Repository;

use App\Entity\Locker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Locker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locker[]    findAll()
 * @method Locker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locker::class);
    }

    // /**
    //  * @return Locker[] Returns an array of Locker objects
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
    public function findOneBySomeField($value): ?Locker
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

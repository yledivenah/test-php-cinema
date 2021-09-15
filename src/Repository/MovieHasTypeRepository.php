<?php

namespace App\Repository;

use App\Entity\MovieHasType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieHasType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieHasType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieHasType[]    findAll()
 * @method MovieHasType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieHasTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieHasType::class);
    }

    // /**
    //  * @return MovieHasType[] Returns an array of MovieHasType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieHasType
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

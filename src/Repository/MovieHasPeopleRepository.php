<?php

namespace App\Repository;

use App\Entity\MovieHasPeople;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**

 * @method MovieHasPeople|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieHasPeople[]    findAll()
 * @method MovieHasPeople[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

/*MovieHasPeople|null find($id, $lockMode = null, $lockVersion = null)*/

class MovieHasPeopleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieHasPeople::class);
    }

    // /**
    //  * @return MovieHasPeople[] Returns an array of MovieHasPeople objects
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
    public function findOneBySomeField($value): ?MovieHasPeople
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

<?php

namespace App\Repository;

use App\Entity\tfchances;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method tfchances|null find($id, $lockMode = null, $lockVersion = null)
 * @method tfchances|null findOneBy(array $criteria, array $orderBy = null)
 * @method tfchances[]    findAll()
 * @method tfchances[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class tfchancesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tfchances::class);
    }

    // /**
    //  * @return tfchances[] Returns an array of tfchances objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?tfchances
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

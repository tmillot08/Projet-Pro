<?php

namespace App\Repository;

use App\Entity\Secretary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Secretary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secretary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secretary[]    findAll()
 * @method Secretary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Secretary::class);
    }

//    /**
//     * @return Secretary[] Returns an array of Secretary objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Secretary
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

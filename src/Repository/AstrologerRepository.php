<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Astrologer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Astrologer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Astrologer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Astrologer[]    findAll()
 * @method Astrologer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AstrologerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Astrologer::class);
    }

    // /**
    //  * @return Astrologer[] Returns an array of Astrologer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Astrologer
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

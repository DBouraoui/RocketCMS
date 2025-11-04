<?php

namespace App\Repository;

use App\Entity\MediaLibrary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaLibrary>
 */
class MediaLibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaLibrary::class);
    }

    public function getTotalSize(): float
    {
        $qb = $this->createQueryBuilder('m')
            ->select('SUM(m.sizeMb) as totalSizeMb')
            ->getQuery()
            ->getSingleScalarResult();

        return (float) $qb;
    }


    //    /**
    //     * @return MediaLibrary[] Returns an array of MediaLibrary objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MediaLibrary
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

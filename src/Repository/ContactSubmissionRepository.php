<?php

namespace App\Repository;

use App\Entity\ContactSubmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactSubmission>
 */
class ContactSubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactSubmission::class);
    }

    public function dashboardCountNotRead(){
        return $this->createQueryBuilder('cs')
            ->select('COUNT(cs.id)')
            ->where('cs.isRead = 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return ContactSubmission[] Returns an array of ContactSubmission objects
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

//    public function findOneBySomeField($value): ?ContactSubmission
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

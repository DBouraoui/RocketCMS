<?php

namespace App\Repository;

use App\Entity\MenuLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuLink>
 */
class MenuLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuLink::class);
    }

    public function getDashboard(): array
    {
        $results = $this->createQueryBuilder('m')
            ->select('m.isActive, COUNT(m.id) AS count')
            ->groupBy('m.isActive')
            ->getQuery()
            ->getResult();

        $dashboard = ['active' => 0, 'inactive' => 0];

        foreach ($results as $row) {
            if ($row['isActive']) {
                $dashboard['active'] = (int) $row['count'];
            } else {
                $dashboard['inactive'] = (int) $row['count'];
            }
        }

        return $dashboard;
    }


//    /**
//     * @return MenuLink[] Returns an array of MenuLink objects
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

//    public function findOneBySomeField($value): ?MenuLink
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

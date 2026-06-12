<?php

namespace App\Repository;

use App\Entity\PacksServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Locataires;

/**
 * @extends ServiceEntityRepository<PacksServices>
 */
class PacksServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PacksServices::class);
    }

    //    /**
    //     * @return PacksServices[] Returns an array of PacksServices objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PacksServices
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findPackServicePourDate(
        Locataires $locataire,
        \DateTimeInterface $date
    ): ?PacksServices
    {
        return $this->createQueryBuilder('p')
            ->where('p.LocatairesID = :locataire')
            ->andWhere('p.date_MES <= :date')
            ->setParameter('locataire', $locataire)
            ->setParameter('date', $date)
            ->orderBy('p.date_MES', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

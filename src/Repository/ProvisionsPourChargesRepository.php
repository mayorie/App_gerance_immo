<?php

namespace App\Repository;

use App\Entity\ProvisionsPourCharges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Locataires;

/**
 * @extends ServiceEntityRepository<ProvisionsPourCharges>
 */
class ProvisionsPourChargesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProvisionsPourCharges::class);
    }

    //    /**
    //     * @return ProvisionsPourCharges[] Returns an array of ProvisionsPourCharges objects
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

    //    public function findOneBySomeField($value): ?ProvisionsPourCharges
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findChargePourDate(
        Locataires $locataire,
        \DateTimeInterface $date
    ): ?ProvisionsPourCharges
    {
        return $this->createQueryBuilder('c')
            ->where('c.LocatairesID = :locataire')
            ->andWhere('c.date_MES <= :date')
            ->setParameter('locataire', $locataire)
            ->setParameter('date', $date)
            ->orderBy('c.date_MES', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

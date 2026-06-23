<?php

namespace App\Repository;

use App\Entity\LoyersHC;
use App\Entity\Locataires;
use App\Repository\Traits\PaginableRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LoyersHC>
 */
class LoyersHCRepository extends ServiceEntityRepository
{
    use PaginableRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoyersHC::class);
    }
    
    //    /**
    //     * @return LoyersHC[] Returns an array of LoyersHC objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LoyersHC
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findLoyerPourDate(
        Locataires $locataire,
        \DateTimeInterface $date
    ): ?LoyersHC
    {
        return $this->createQueryBuilder('l')
            ->where('l.LocatairesID = :locataire')
            ->andWhere('l.date_MES <= :date')
            ->setParameter('locataire', $locataire)
            ->setParameter('date', $date)
            ->orderBy('l.date_MES', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\PaiementsMensuels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaiementsMensuels>
 */
class PaiementsMensuelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaiementsMensuels::class);
    }

    //    /**
    //     * @return PaiementsMensuels[] Returns an array of PaiementsMensuels objects
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

    //    public function findOneBySomeField($value): ?PaiementsMensuels
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findFirstPaiementsIds(): array
    {
        return $this->createQueryBuilder('p')
            ->select('MIN(p.id) as id')
            ->groupBy('p.LocatairesID')
            ->getQuery()
            ->getScalarResult();
    }

    public function findLoyerHC(PaiementsMensuels $paiement): ?float
    {
        $loyer = $this->getEntityManager()
            ->getRepository(\App\Entity\LoyersHC::class)
            ->createQueryBuilder('l')

            ->andWhere('l.LocatairesID = :locataire')
            ->andWhere('l.date_MES <= :datePaiement')

            ->setParameter('locataire', $paiement->getLocatairesID())
            ->setParameter('datePaiement', $paiement->getDate())

            ->orderBy('l.date_MES', 'DESC')

            ->setMaxResults(1)

            ->getQuery()
            ->getOneOrNullResult();

        return $loyer ? $loyer->getMontant() : null;
    }
}

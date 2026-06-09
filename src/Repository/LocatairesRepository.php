<?php

namespace App\Repository;

use App\Entity\Locataires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Locataires>
 */
class LocatairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locataires::class);
    }

    //    /**
    //     * @return Locataires[] Returns an array of Locataires objects
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

    //    public function findOneBySomeField($value): ?Locataires
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAyantPaiementMoisEtAnnee(
        int $mois,
        int $annee
    ): array
    {
        $debut = new \DateTimeImmutable(sprintf(
            '%04d-%02d-01 00:00:00',
            $annee,
            $mois
        ));

        $fin = $debut->modify('first day of next month');

        return $this->createQueryBuilder('l')
            ->distinct()
            ->join('l.Paiements_mensuels', 'p')
            ->andWhere('p.date >= :debut')
            ->andWhere('p.date < :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getResult();
    }
}

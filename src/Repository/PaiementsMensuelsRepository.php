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

    public function findProvisionPourCharges(PaiementsMensuels $paiement): ?float
    {
        $charge = $this->getEntityManager()
            ->getRepository(\App\Entity\ProvisionsPourCharges::class)
            ->createQueryBuilder('c')

            ->andWhere('c.LocatairesID = :locataire')
            ->andWhere('c.date_MES <= :datePaiement')

            ->setParameter('locataire', $paiement->getLocatairesID())
            ->setParameter('datePaiement', $paiement->getDate())

            ->orderBy('c.date_MES', 'DESC')

            ->setMaxResults(1)

            ->getQuery()
            ->getOneOrNullResult();

        return $charge ? $charge->getMontant() : null;
    }

    public function findPackService(PaiementsMensuels $paiement): ?float
    {
        $pack = $this->getEntityManager()
            ->getRepository(\App\Entity\PacksServices::class)
            ->createQueryBuilder('p')

            ->andWhere('p.LocatairesID = :locataire')
            ->andWhere('p.date_MES <= :datePaiement')

            ->setParameter('locataire', $paiement->getLocatairesID())
            ->setParameter('datePaiement', $paiement->getDate())

            ->orderBy('p.date_MES', 'DESC')

            ->setMaxResults(1)

            ->getQuery()
            ->getOneOrNullResult();

        return $pack ? $pack->getMontant() : null;
    }

    public function findByLocataireMoisEtAnnee(
        int $locataireId,
        int $mois,
        int $annee
    ): array
    {
        $debut = new \DateTime(sprintf(
            '%04d-%02d-01 00:00:00',
            $annee,
            $mois
        ));

        $fin = (clone $debut)->modify('first day of next month');

        return $this->createQueryBuilder('p')
            ->andWhere('p.LocatairesID = :locataire')
            ->andWhere('p.date >= :debut')
            ->andWhere('p.date < :fin')
            ->setParameter('locataire', $locataireId)
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLastPaiementPreviousMonth(
        int $locataireId,
        int $mois,
        int $annee
    ): ?PaiementsMensuels
    {
        $debutMois = new \DateTimeImmutable(sprintf(
            '%04d-%02d-01 00:00:00',
            $annee,
            $mois
        ));

        return $this->createQueryBuilder('p')
            ->andWhere('p.LocatairesID = :locataire')
            ->andWhere('p.date < :debutMois')
            ->setParameter('locataire', $locataireId)
            ->setParameter('debutMois', $debutMois)
            ->orderBy('p.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

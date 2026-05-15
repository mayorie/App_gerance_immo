<?php

namespace App\Repository;

use App\Entity\RBTBailleur;
use App\Entity\PaiementsMensuels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RBTBailleur>
 */
class RBTBailleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RBTBailleur::class);
    }

    //    /**
    //     * @return RBTBailleur[] Returns an array of RBTBailleur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RBTBailleur
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByPaiement(PaiementsMensuels $paiement): ?RBTBailleur
    {
        return $this->createQueryBuilder('r')

            ->andWhere('r.Paiements_mensuelID = :paiement')

            ->setParameter('paiement', $paiement)

            ->setMaxResults(1)

            ->getQuery()

            ->getOneOrNullResult();
    }
}

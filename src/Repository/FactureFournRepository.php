<?php

namespace App\Repository;

use App\Entity\FactureFourn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactureFourn>
 */
class FactureFournRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureFourn::class);
    }

    public function save(FactureFourn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FactureFourn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAnnee(?int $annee): array
    {
        if ($annee === null) {
            return $this->findAll();
        }

        $dateDebut = new \DateTime("$annee-01-01");
        $dateFin = new \DateTime("$annee-12-31");

        return $this->createQueryBuilder('f')
            ->where('f.date_facture >= :dateDebut')
            ->andWhere('f.date_facture <= :dateFin')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->orderBy('f.date_facture', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllAnnees(): array
    {
        $factures = $this->findAll();
        $annees = [];
        foreach ($factures as $facture) {
            if ($facture->getDateFacture()) {
                $annees[$facture->getDateFacture()->format('Y')] = true;
            }
        }
        krsort($annees);
        return array_map(function($annee) {
            return ['annee' => $annee];
        }, array_keys($annees));
    }
}

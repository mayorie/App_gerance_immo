<?php

namespace App\Repository;

use App\Entity\NoteDeFrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoteDeFrais>
 */
class NoteDeFraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteDeFrais::class);
    }

    public function save(NoteDeFrais $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NoteDeFrais $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAnnee(?int $annee): array
    {
        if ($annee === null) {
            return $this->createQueryBuilder('n')
                ->orderBy('n.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        $dateDebut = new \DateTimeImmutable("$annee-01-01 00:00:00");
        $dateFin = new \DateTimeImmutable("$annee-12-31 23:59:59");

        return $this->createQueryBuilder('n')
            ->where('n.date >= :dateDebut')
            ->andWhere('n.date <= :dateFin')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->orderBy('n.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllAnnees(): array
    {
        $notes = $this->findAll();
        $annees = [];
        foreach ($notes as $note) {
            if ($note->getDate()) {
                $annees[$note->getDate()->format('Y')] = true;
            }
        }
        krsort($annees);
        return array_map(function($annee) {
            return ['annee' => $annee];
        }, array_keys($annees));
    }
}

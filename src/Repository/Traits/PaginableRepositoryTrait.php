<?php

namespace App\Repository\Traits;

use App\Entity\Locataires;

trait PaginableRepositoryTrait
{
    public function findAllPaginated(int $page, int $limit): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date_MES', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByLocatairePaginated(Locataires $locataire, int $page, int $limit): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.LocatairesID = :locataire')
            ->setParameter('locataire', $locataire)
            ->orderBy('e.date_MES', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByLocataireIdPaginated(int $locataireId, int $page, int $limit): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.LocatairesID = :locataire')
            ->setParameter('locataire', $locataireId)
            ->orderBy('e.date_MES', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByLocataire(Locataires $locataire): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.LocatairesID = :locataire')
            ->setParameter('locataire', $locataire)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByLocataireId(int $locataireId): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.LocatairesID = :locataire')
            ->setParameter('locataire', $locataireId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

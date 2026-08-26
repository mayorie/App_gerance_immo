<?php

namespace App\Repository;

use App\Entity\Pcg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pcg>
 */
class PcgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pcg::class);
    }

    public function save(Pcg $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pcg $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Pcg[]
     */
    public function findByPrefix(string $prefix): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.compte LIKE :prefix')
            ->setParameter('prefix', $prefix . '%')
            ->orderBy('p.compte', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

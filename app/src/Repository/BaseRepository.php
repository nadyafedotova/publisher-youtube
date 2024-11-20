<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }
    public function save(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function remove(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function saveAndCommit(object $entity): void
    {
        $this->save($entity);
        $this->commit();
    }

    public function removeAndCommit(object $entity): void
    {
        $this->remove($entity);
        $this->commit();
    }

    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }
}

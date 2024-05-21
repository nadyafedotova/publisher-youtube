<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BookCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookCategory>
 */
class BookCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }

    /**
     * @return BookCategory[]
     */
    final public function findAllSortedByTitle(): array
    {
        return $this->findBy([], ['title' => 'ASC']);
    }

    public function existsById(int $id): bool
    {
        return null !== $this->find($id);
    }
}

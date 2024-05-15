<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BookCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookCategory>
 * @method find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 */
class BookCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookCategory>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }

    /**
     * @param int $id
     * @return Book[]
     */
    public function findBooksByCategoryId(int $id): array
    {
        $queryBuilder = $this->getEntityManager()->createQuery('SELECT b FROM App\Entity\Book b WHERE :categoryId MEMBER OF b.categories');
        $queryBuilder->setParameter('categoryId', $id);

        return $queryBuilder->getResult();
    }
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\AbstractUnicodeString;

class BookCategoryRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }

    /** @return BookCategory[] */
    public function findBookCategoriesByIds(array $ids): array
    {
        return $this->findBy(['id' => $ids]);
    }
    /**  @return BookCategory[] */
    public function findAllSortedByTitle(): array
    {
        return $this->findBy([], ['title' => 'ASC']);
    }

    public function existsById(int $id): bool
    {
        return null !== $this->find($id);
    }

    public function getById(int $id): BookCategory
    {
        $category = $this->find($id);
        if (null === $category) {
            throw new BookCategoryNotFoundException();
        }

        return $category;
    }

    public function countBooksInCategory(int $categoryId): int
    {
        return $this->getEntityManager()->createQuery('SELECT COUNT(b.id) FROM App\Entity\Book b JOIN b.categories c WHERE c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getSingleScalarResult();
    }

    public function existsBySlug(AbstractUnicodeString $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}

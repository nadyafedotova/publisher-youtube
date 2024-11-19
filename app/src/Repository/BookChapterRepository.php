<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookChapter;
use App\Exception\BookChapterNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class BookChapterRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookChapter::class);
    }

    final public function getById(int $id): BookChapter
    {
        $chapter = $this->find($id);
        if (null === $chapter) {
            throw new BookChapterNotFoundException();
        }

        return $chapter;
    }

    final public function getMaxSort(Book $book, int $level): int
    {
        return (int) $this->getEntityManager()->createQuery('SElECT MAX(c.sort) FROM App\Entity\BookChapter c WHERE c.book = :book AND c.level = :level')
            ->setParameter('book', $book)
            ->setParameter('level', $level)
            ->getSingleScalarResult();
    }

    final public function increasesSortFrom(int $sortStart, Book $book, int $level, int $sortStep = 1): void
    {
        $this->getEntityManager()->createQuery('UPDATE App\Entity\BookChapter c SET c.sort = c.sort + :sortStep WHERE c.sort >= :sortStart AND c.book = :book AND c.level = :level')
            ->setParameter('sortStart', $sortStart)
            ->setParameter('book', $book)
            ->setParameter('level', $level)
            ->setParameter('sortStep', $sortStep)
            ->execute();
    }

    /**
     * @return BookChapter[]
     */
    final public function findSortedChaptersByBook(Book $book): array
    {
        return $this->findBy(['book' => $book], ['level' => 'ASC', 'sort' => 'ASC']);
    }
}

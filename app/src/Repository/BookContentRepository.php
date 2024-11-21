<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BookContent;
use App\Exception\BookChapterContentNotFoundException;
use Countable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Traversable;

class BookContentRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookContent::class);
    }

    //todo replace
    public function getById(int $id): BookContent
    {
        $content = $this->find($id);
        if (null === $content) {
            throw new BookChapterContentNotFoundException();
        }

        return $content;
    }

    public function getPageByChapterId(int $id, bool $onlyPublished, int $offset, int $limit): Traversable&Countable
    {
        $query = implode(' ', array_filter([
            'SELECT b FROM App\Entity\BookContent b WHERE b.chapter = :id',
            $onlyPublished ? 'AND b.isPublished = true' : null,
            ' ORDER BY b.id ASC',
        ]));

        $query = $this->getEntityManager()
            ->createQuery($query)
            ->setParameter('id', $id)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($query, false);
    }

    public function countByChapterId(int $id, bool $onlyPublished): int
    {
        $condition = ['chapter' => $id];
        if ($onlyPublished) {
            $condition['isPublished'] = true;
        }

        return count($condition);
    }
}

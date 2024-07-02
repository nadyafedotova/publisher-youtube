<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Review;
use Countable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Traversable;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry $registry
    ) {
        parent::__construct($registry, Review::class);
    }

    final public function countByBookId(int $bookId): int
    {
        return $this->count(['book' => $bookId]);
    }

    public function getBookTotalRatingSum(int $bookId): int
    {
        return (int) $this->registry->getManager()->createQuery(
            'SELECT SUM(r.rating) FROM App\Entity\Review r WHERE r.book = :id'
        )->setParameter('id', $bookId)
            ->getSingleScalarResult();
    }

    public function getPageByBookId(int $id, int $offset, int $limit): Traversable|Countable
    {
        $query = $this->registry->getManager()->createQuery(
            'SELECT r FROM App\Entity\Review r WHERE r.book = :id ORDER BY r.createdAt DESC'
        )->setParameter('id', $id)
        ->setFirstResult($offset)
        ->setMaxResults($limit);

        return new Paginator($query, false);
    }
}

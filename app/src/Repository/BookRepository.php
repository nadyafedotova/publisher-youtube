<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\AbstractUnicodeString;

class BookRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param int $id
     * @return Book[]
     */
    public function findPublishedBooksByCategoryId(int $id): array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b FROM App\Entity\Book b WHERE :categoryId MEMBER OF b.categories AND b.publicationDate IS NOT NULL')
            ->setParameter('categoryId', $id)
            ->getResult();
    }

    public function getPublishedById(int $id): Book
    {
        $book = $this->getEntityManager()
            ->createQuery('SELECT b FROM App\Entity\Book b WHERE b.id = :id MEMBER OF b.categories AND b.publicationDate IS NOT NULL')
            ->setParameter('id', $id)
            ->getOneOrNullResult();

        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    /**
     * @return Book[]
     */
    public function findBooksByIds(array $ids): array
    {
        return $this->getEntityManager()
           ->createQuery('SELECT b FROM App\Entity\Book b WHERE b.id = :ids MEMBER OF b.categories AND b.publicationDate IS NOT NULL')
           ->setParameter('id', $ids)
           ->getResult();
    }

    public function findUserBooks(UserInterface $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function getBookById(int $id): Book
    {
        $book = $this->find($id);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    public function existsBySlug(AbstractUnicodeString $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }

    public function existsUserBookById(int $id, UserInterface $user)
    {
        return null !== $this->findOneBy(['id' => $id, 'user' => $user]);
    }
}

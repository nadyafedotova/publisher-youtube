<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;

class BooksService
{
    public function __construct(
        private BookRepository $bookRepository,
        private readonly BookCategoryRepository $bookCategoryRepository,
    )
    {
    }

    final public function getBooksByCategory(int $categoryId): BookListResponse
    {
        $category = $this->bookCategoryRepository->find($categoryId);

        if (null === $category) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(array_map(
            [$this, 'map'],
            $this->bookRepository->findBooksByCategoryId($categoryId)
        ));
    }

    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setAuthors($book->getAuthors())
            ->setMeap($book->isMeap())
            ->setPublicationDate($book->getPublicationDate()->getTimestamp());
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Mapper\BookMapper;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\BookDetails;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;

readonly class BooksService
{
    public function __construct(
        private BookRepository           $bookRepository,
        private BookCategoryRepository   $bookCategoryRepository,
        private RatingService            $ratingService,
    ) {
    }

    final public function getBooksByCategory(int $categoryId): BookListResponse
    {
        if (!$this->bookCategoryRepository->existsById($categoryId)) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(array_map(
            fn (Book $book) => BookMapper::map($book, new BookListItem()),
            $this->bookRepository->findPublishedBooksByCategoryId($categoryId),
        ));
    }

    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->getPublishedById($id);
        $rating = $this->ratingService->calcReviewRatingForBook($id);

        $bookMapper = BookMapper::map($book, new BookDetails());
        $bookMapper->setRating($rating->getRating());
        $bookMapper->setReviews($rating->getTotal());
        $bookMapper->setFormats(BookMapper::mapFormats($book));
        $bookMapper->setCategories(BookMapper::mapCategories($book));

        return $bookMapper;
    }
}

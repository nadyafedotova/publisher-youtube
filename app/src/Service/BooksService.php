<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Mapper\BookMapper;
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
            fn (Book $book) => BookMapper::map($book, new BookDetails()),
            $this->bookRepository->findPublishedBooksByCategoryId($categoryId),
        ));
    }

    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->getPublishedById($id);
        $rating = $this->ratingService->calcReviewRatingForBook($id);
        $details = new BookDetails();
        BookMapper::map($book, $details);

        return
            $details->setRating($rating->getRating())
                ->setReviews($rating->getTotal())
                ->setFormats(BookMapper::mapFormats($book))
                ->setCategories(BookMapper::mapCategories($book));
    }
}

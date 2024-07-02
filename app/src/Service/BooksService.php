<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Mapper\BookMapper;
use App\Model\BookCategory as BookCategoryModel;
use App\Entity\BookToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookDetails;
use App\Model\BookFormat;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\Collection;

readonly class BooksService
{
    public function __construct(
        private BookRepository         $bookRepository,
        private BookCategoryRepository $bookCategoryRepository,
        private ReviewRepository       $reviewRepository,
        private RatingService          $ratingService,
    ) {
    }

    final public function getBooksByCategory(int $categoryId): BookListResponse
    {
        if (!$this->bookCategoryRepository->existsById($categoryId)) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(array_map(
            fn (Book $book) => BookMapper::map($book, new BookListItem()),
            $this->bookRepository->findBooksByCategoryId($categoryId),
        ));
    }

    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->getById($id);
        $reviews = $this->reviewRepository->countByBookId($id);

        $categories = $book->getCategories()
            ->map(
                fn (BookCategory $bookCategory) => new BookCategoryModel(
                    $bookCategory->getId(),
                    $bookCategory->getTitle(),
                    $bookCategory->getSlug(),
                ),
            );

        $bookMapper = BookMapper::map($book, new BookDetails());
        $bookMapper->setRating($this->ratingService->calcReviewRatingForBook($id, $reviews));
        $bookMapper->setReviews($reviews);
        $bookMapper->setFormats($this->mapFormats($book->getFormats()));
        $bookMapper->setCategories($categories->toArray());

        return $bookMapper;
    }

    /**
     * @param Collection<BookToBookFormat> $formats
     * @return array
     */
    private function mapFormats(Collection $formats): array
    {
        return $formats->map(
            fn (BookToBookFormat $formatJoin) => $this->createBookFormat($formatJoin),
        )->toArray();
    }

    private function createBookFormat(BookToBookFormat $format): BookFormat
    {
        $bookFormat = new BookFormat();
        $bookFormat->setId($format->getFormat()->getId());
        $bookFormat->setTitle($format->getFormat()->getTitle());
        $bookFormat->setDescription($format->getFormat()->getDescription());
        $bookFormat->setComment($format->getFormat()->getComment());
        $bookFormat->setPrice($format->getPrice());
        $bookFormat->setDiscountPercent($format->getDiscountPercent());

        return $bookFormat;
    }
}

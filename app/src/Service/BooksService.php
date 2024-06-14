<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
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
    ) {
    }

    final public function getBooksByCategory(int $categoryId): BookListResponse
    {
        if (!$this->bookCategoryRepository->existsById($categoryId)) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(array_map(
            [$this, 'map'],
            $this->bookRepository->findBooksByCategoryId($categoryId),
        ));
    }

    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->getById($id);
        $reviews = $this->reviewRepository->countByBookId($id);

        $rating = 0;
        if ($reviews > 0) {
            $rating = $this->reviewRepository->getBookTotalRatingSum($id);
        }

        $categories = $book->getCategories()
            ->map(
                fn (BookCategory $bookCategory) => new BookCategoryModel(
                    $bookCategory->getId(),
                    $bookCategory->getTitle(),
                    $bookCategory->getSlug(),
                ),
            );

        $bookDetails = new BookDetails();
        $bookDetails->setId($book->getId());
        $bookDetails->setTitle($book->getTitle());
        $bookDetails->setSlug($book->getSlug());
        $bookDetails->setImage($book->getImage());
        $bookDetails->setAuthors($book->getAuthors());
        $bookDetails->setMeap($book->isMeap());
        $bookDetails->setPublicationDate($book->getPublicationDate()->getTimestamp());
        $bookDetails->setRating($rating);
        $bookDetails->setReviews($reviews);
        $bookDetails->setFormats((array)$this->mapFormats($book->getFormats()));
        $bookDetails->setCategories($categories->toArray());

        return $bookDetails;
    }

    /**
     * @param Collection<BookToBookFormat> $formats
     * @return Collection
     */
    private function mapFormats(Collection $formats): Collection
    {
        return $formats->map(fn (BookToBookFormat $formatJoin) => $this->createBookFormat($formatJoin));
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

    private function map(Book $book): BookListItem
    {
        $bookListItem = new BookListItem();
        $bookListItem->setTitle($book->getTitle());
        $bookListItem->setSlug($book->getSlug());
        $bookListItem->setImage($book->getImage());
        $bookListItem->setAuthors($book->getAuthors());
        $bookListItem->setMeap($book->isMeap());
        $bookListItem->setPublicationDate($book->getPublicationDate()->getTimestamp());

        return $bookListItem;
    }
}

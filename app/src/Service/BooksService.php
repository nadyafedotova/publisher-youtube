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
use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\Model\RecommendationItem;
use App\Service\Recommendation\RecommendationService;
use Doctrine\Common\Collections\Collection;
use Exception;
use Psr\Log\LoggerInterface;

readonly class BooksService
{
    public function __construct(
        private BookRepository         $bookRepository,
        private BookCategoryRepository $bookCategoryRepository,
        private ReviewRepository       $reviewRepository,
        private RatingService          $ratingService,
        private RecommendationService  $recommendationService,
        private LoggerInterface $logger
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
        $recommendations = [];

        $categories = $book->getCategories()
            ->map(
                fn (BookCategory $bookCategory) => new BookCategoryModel(
                    $bookCategory->getId(),
                    $bookCategory->getTitle(),
                    $bookCategory->getSlug(),
                )
            );

        try {
            $recommendations = $this->getRecommendations($id);
        } catch (Exception $ex) {
            $this->logger->error('error while fetching recommendations', [
                'exception' => $ex->getMessage(),
                'bookId' => $id,
            ]);
        }

        $bookMapper = BookMapper::map($book, new BookDetails());
        $bookMapper->setRating($this->ratingService->calcReviewRatingForBook($id, $reviews));
        $bookMapper->setReviews($reviews);
        $bookMapper->setRecommendations($recommendations);
        $bookMapper->setFormats($this->mapFormats($book->getFormats()));
        $bookMapper->setCategories($categories->toArray());

        return $bookMapper;
    }

    /**
     * @throws RequestException
     * @throws AccessDeniedException
     */
    private function getRecommendations(int $bookId): array
    {
        $this->recommendationService->getRecommendationsByBookId($bookId)->getRecommendations();
        $ids = array_map(
            fn (RecommendationItem $item) => $item->getId(),
            $this->recommendationService->getRecommendationsByBookId($bookId)->getRecommendations()

        );

        return array_map(
            [BookMapper::class, 'mapRecommended'],
            $this->bookRepository->findBooksByIds($ids)
        );
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

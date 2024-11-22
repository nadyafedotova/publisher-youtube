<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Review;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use Exception;

class ReviewService
{
    private const int PAGE_LIMIT = 5;

    public function __construct(
        private readonly ReviewRepository $reviewRepository,
        private readonly RatingService    $ratingService,
    ) {
    }

    /** @throws Exception */
    final public function getReviewPageByBookId(int $id, int $page): ReviewPage
    {
        $items = [];
        $paginator = $this->reviewRepository->getPageByBookId(
            $id,
            PaginationUtils::calcOOffset($page, self::PAGE_LIMIT),
            self::PAGE_LIMIT
        );

        foreach ($paginator as $item) {
            $items[] = $this->map($item);
        }

        $rating = $this->ratingService->calcReviewRatingForBook($id);
        $total =$rating->getTotal();

        return (new ReviewPage())
            ->setRating($rating->getRating())
            ->setTotal($rating->getTotal())
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages(PaginationUtils::calcPages($total, self::PAGE_LIMIT))
            ->setItems($items);
    }

    public function map(Review $review): ReviewModel
    {
        return (new ReviewModel())
            ->setId($review->getId())
            ->setRating($review->getRating())
            ->setAuthor($review->getAuthor())
            ->setContent($review->getContent());
    }
}

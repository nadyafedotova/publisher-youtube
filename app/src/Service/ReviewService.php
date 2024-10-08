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
        private readonly RatingService $ratingService
    ) {
    }

    /**
     * @throws Exception
     */
    final public function getReviewPageByBookId(int $id, int $page): ReviewPage
    {
        $offset = max($page - 1, 0) * self::PAGE_LIMIT;
        $paginator = $this->reviewRepository->getPageByBookId($id, $offset, self::PAGE_LIMIT);
        $total = count($paginator);
        $items = [];

        foreach ($paginator as $item) {
            $items[] = $this->map($item);
        }

        $rating = $this->ratingService->calcReviewRatingForBook($id);

        $reviewPage = new ReviewPage();
        $reviewPage->setRating($rating->getRating());
        $reviewPage->setTotal($rating->getTotal());
        $reviewPage->setPage($page);
        $reviewPage->setPerPage(self::PAGE_LIMIT);
        $reviewPage->setPages((int)ceil($total / self::PAGE_LIMIT));
        $reviewPage->setItems($items);

        return $reviewPage;
    }

    public function map(Review $review): ReviewModel
    {
        $reviewModel = new ReviewModel();
        $reviewModel->setId($review->getId());
        $reviewModel->setRating($review->getRating());
        $reviewModel->setAuthor($review->getAuthor());
        $reviewModel->setContent($review->getContent());

        return $reviewModel;
    }
}

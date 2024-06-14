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

        $rating = 0;
        if ($total > 0) {
            $rating = $this->reviewRepository->getBookTotalRatingSum($id) / $total;
        }

        $reviewPage = new ReviewPage();
        $reviewPage->setRating($rating);
        $reviewPage->setTotal($total);
        $reviewPage->setPage($page);
        $reviewPage->setPerPage(self::PAGE_LIMIT);
        $reviewPage->setPages((int)ceil($total / self::PAGE_LIMIT));
        $reviewPage->setItems(array_map([$this, 'map'], $paginator->getIterator()->getArrayCopy()));

        return $reviewPage;
    }

    public function map(Review $review): ReviewModel
    {
        $reviewModel = new ReviewModel();
        $reviewModel->setId($review->getId());
        $reviewModel->setRating($review->getRating());
        $reviewModel->setCreatedAt($review->getCreatedAt());
        $reviewModel->setAuthor($review->getAuthor());
        $reviewModel->setContent($review->getContent());

        return $reviewModel;
    }
}

<?php

namespace App\Tests\src\Service;

use App\Entity\Review;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Service\ReviewService;
use App\Tests\AbstractTestCase;
use ArrayIterator;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;

class ReviewServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;

    private RatingService $ratingService;
    private const int BOOK_ID = 1;

    private const int PER_PAGE = 5;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }

    public static function dataProvider(): array
    {
        return [
            [0, 0],
            [-1, 0],
            [-20, 0],
        ];
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('dataProvider')]
    public function testGetReviewPageByBookIdInvalidPage(int $page, int $offset): void
    {
        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(self::BOOK_ID, 0)
            ->willReturn(0.0);

        $this->reviewRepository->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, $offset, self::PER_PAGE)
            ->willReturn(new ArrayIterator());

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $expected = new ReviewPage();
        $expected->setTotal(0);
        $expected->setRating(0);
        $expected->setPage($page);
        $expected->setPages(0);
        $expected->setPerPage(self::PER_PAGE);
        $expected->setItems([]);
        $this->assertEquals($expected, $service->getReviewPageByBookId(self::BOOK_ID, $page));
    }

    /**
     * @throws \Exception
     */
    public function testGetReviewPageByBookId(): void
    {
        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(self::BOOK_ID, 1)
            ->willReturn(4.0);

        $review = new Review();
        $review->setAuthor('tester');
        $review->setContent('test');
        $review->setCreatedAt(new DateTimeImmutable(2024-06-27));
        $review->setRating(4);

        $this->setEntityId($review, 1);

        $this->reviewRepository->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, 0, self::PER_PAGE)
            ->willReturn(new ArrayIterator([$review]));

        $reviewModel = new ReviewModel();
        $reviewModel->setId(1);
        $reviewModel->setRating(4);
        $reviewModel->setCreatedAt(new DateTimeImmutable(2024-06-27));
        $reviewModel->setContent('test');
        $reviewModel->setAuthor('tester');

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $expected = new ReviewPage();
        $expected->setTotal(1);
        $expected->setRating(4);
        $expected->setPage(1);
        $expected->setPages(1);
        $expected->setPerPage(self::PER_PAGE);
        $expected->setItems([$reviewModel]);

        $this->assertEquals($expected, $service->getReviewPageByBookId(self::BOOK_ID, 1));
    }
}

<?php

namespace App\Tests\src\Service;

use App\Model\Rating;
use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Service\ReviewService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use ArrayIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;

class ReviewServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;
    private RatingService $ratingService;
    private MockUtils $entityTest;

    private const int BOOK_ID = 1;
    private const int PER_PAGE = 5;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->entityTest = new MockUtils();
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
    final public function testGetReviewPageByBookIdInvalidPage(int $page, int $offset): void
    {
        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(self::BOOK_ID)
            ->willReturn(new Rating(0, 0.0));

        $this->reviewRepository->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, $offset, self::PER_PAGE)
            ->willReturn(new ArrayIterator());

        $service = new ReviewService($this->reviewRepository, $this->ratingService);

        $this->assertEquals($this->entityTest->createReviewPage(0, 0, $page, 0, []), $service->getReviewPageByBookId(self::BOOK_ID, $page));
    }

    /**
     * @throws \Exception
     */
    final public function testGetReviewPageByBookId(): void
    {
        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(self::BOOK_ID)
            ->willReturn(new Rating(1, 4.0));

        $review = $this->entityTest->createReview($this->entityTest->createBook());
        $review->setAuthor('tester');
        $review->setContent('test');
        $review->setRating(4);

        $this->reviewRepository->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, 0, self::PER_PAGE)
            ->willReturn(new ArrayIterator([$review]));

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $reviewPage = $this->entityTest->createReviewPage(1, 4, 1, 1, array($this->entityTest->createReviewModel()));

        $this->assertEquals($reviewPage, $service->getReviewPageByBookId(self::BOOK_ID, 1));
    }
}

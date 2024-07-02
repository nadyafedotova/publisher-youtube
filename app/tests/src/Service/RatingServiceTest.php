<?php

namespace App\Tests\src\Service;

use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;

class RatingServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
    }

    public static function provider(): array
    {
        return [
            [25, 20, 1.25],
            [0, 5, 0],
        ];
    }

    #[DataProvider('provider')]
    final public function testCalcReviewRatingForBook(int $repositoryRatingSue, int $total, float $expectedRating): void
    {
        $this->reviewRepository->expects($this->once())
            ->method('getBookTotalRatingSum')
            ->with(1)
            ->willReturn($repositoryRatingSue);

        $this->assertEquals(
            $expectedRating,
            (new RatingService($this->reviewRepository))->calcReviewRatingForBook(1, $total)
        );
    }

    final public function testCalcReviewRatingForBookZeroTotal(): void
    {
        $this->reviewRepository->expects($this->never())
            ->method('getBookTotalRatingSum');

        $this->assertEquals(
            0,
            (new RatingService($this->reviewRepository))->calcReviewRatingForBook(1, 0)
        );
    }
}

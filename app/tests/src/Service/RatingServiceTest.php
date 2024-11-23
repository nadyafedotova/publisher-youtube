<?php

namespace App\Tests\src\Service;

use App\Model\Rating;
use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;

class RatingServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;

    /** @throws Exception */
    final protected function setUp(): void
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

        $this->reviewRepository->expects($this->once())
            ->method('countByBookId')
            ->with(1)
            ->willReturn($total);

        $this->assertEquals(
            new Rating($total, $expectedRating),
            (new RatingService($this->reviewRepository))->calcReviewRatingForBook(1)
        );
    }

    final public function testCalcReviewRatingForBookZeroTotal(): void
    {
        $this->reviewRepository->expects($this->never())
            ->method('getBookTotalRatingSum');

        $this->reviewRepository->expects($this->once())
            ->method('countByBookId')
            ->with(1)
            ->willReturn(0);


        $this->assertEquals(
            new Rating(0, 0),
            (new RatingService($this->reviewRepository))->calcReviewRatingForBook(1)
        );
    }
}

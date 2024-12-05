<?php

namespace App\Tests\src\Service;

use App\Model\RecommendedBookListResponse;
use App\Repository\BookRepository;
use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\Model\RecommendationItem;
use App\Service\Recommendation\Model\RecommendationResponse;
use App\Service\Recommendation\RecommendationApiService;
use App\Service\RecommendationService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use Random\RandomException;
use ReflectionException;

class RecommendationServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;
    private RecommendationApiService $recommendationApiService;

    /** @throws Exception */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->recommendationApiService = $this->createMock(RecommendationApiService::class);
    }

    public static function dataProvider(): array
    {
        //try with <<<EOF
        return [
            ['short description', 'short description'],
            [
                "begin long description long description long description long description long long description long description long description long description description",
                "begin long description long description long description long description long long description long description long description long description ..."
            ],
        ];
    }

    /** @throws ReflectionException|RequestException|AccessDeniedException|RandomException */
    #[DataProvider('dataProvider')]
    final public function testGetRecommendationsByBookId(string $actualDescription, string $expectedDescription): void
    {
        $book = MockUtils::createBook()->setDescription($actualDescription);
        MockUtils::setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('findBooksByIds')
            ->with([2])
            ->willReturn([$book]);

        $this->recommendationApiService->expects($this->once())
            ->method('getRecommendationsByBookId')
            ->with(1)
            ->willReturn(new RecommendationResponse(1, 12345, [new RecommendationItem(2),]));

        $expected = (new RecommendedBookListResponse([MockUtils::createRecommendedBook()->setShortDescription($expectedDescription)]))->getItems()[0];
        $db = $this->createService()->getRecommendationsByBookId(1)->getItems()[0];

        $expected->setSlug($db->getSlug());
        $this->assertEquals($expected, $db);
    }

    private function createService(): RecommendationService
    {
        return new RecommendationService($this->bookRepository, $this->recommendationApiService);
    }
}

<?php

namespace App\Tests\src\Service\Recommendation;

use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\RecommendationApiService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RecommendationApiServiceTest extends AbstractTestCase
{
    private SerializerInterface $serializer;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    public static function dataProvider(): array
    {
        return [
            [Response::HTTP_FORBIDDEN, AccessDeniedException::class],
            [Response::HTTP_CONFLICT, RequestException::class],
        ];
    }


    /**
     * @throws RequestException
     * @throws AccessDeniedException
     */
    #[DataProvider('dataProvider')]
    final public function testGetRecommendationsByBookId(int $responseCode, string $exceptionClass): void
    {
        $this->expectException($exceptionClass);

        $httpClient = new MockHttpClient(
            new MockResponse('', ['http_code' => $responseCode]),
            'https://localhost/'
        );

        (new RecommendationApiService($httpClient, $this->serializer))->getRecommendationsByBookId(1);
    }
}

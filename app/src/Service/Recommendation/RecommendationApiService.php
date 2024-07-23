<?php

declare(strict_types=1);

namespace App\Service\Recommendation;

use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\Model\RecommendationResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

readonly class RecommendationApiService
{
    public function __construct(
        private HttpClientInterface $recommendationClient,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws RequestException
     * @throws AccessDeniedException
     */
    public function getRecommendationsByBookId(int $bookId): RecommendationResponse
    {
        try {
            $response = $this->recommendationClient->request('GET', '/api/v1/book/' . $bookId . '/recommendations');

            return $this->serializer->deserialize(
                $response->getContent(),
                RecommendationResponse::class,
                'json'
            );
        } catch (Throwable $ex) {
            dd($ex);
            if ($ex instanceof TransportExceptionInterface && Response::HTTP_FORBIDDEN === $ex->getCode()) {
                throw new AccessDeniedException($ex);
            }

            throw new RequestException($ex->getMessage(), $ex);
        }
    }
}
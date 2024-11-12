<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Model\RecommendedBookListResponse;
use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\RecommendationService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class RecommendationController extends AbstractController
{
    public function __construct(
        private RecommendationService  $recommendationService
    ) {
    }

    /**
     * @throws RequestException
     * @throws AccessDeniedException
     */
    #[OA\Response(
        response: 200,
        description: 'Returns recommendations for the book',
        content: new Model(type: RecommendedBookListResponse::class)
    )]
    #[Route(path: '/api/v1/book/{id}/recommendations', methods: ['GET'])]
    public function recommendationsByBookId(int $id): Response
    {
        return $this->json($this->recommendationService->getRecommendationsByBookId($id));
    }
}

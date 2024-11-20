<?php

namespace App\Controller;

use App\Model\ReviewPage;
use App\Service\ReviewService;
use Exception;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class ReviewController extends AbstractController
{
    public function __construct(
        private readonly ReviewService $reviewService,
    ) {
    }

    /** @throws Exception */
    #[OA\Parameter(name: "page", description: 'Page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Returns page of reviews for the given book', content: new Model(type: ReviewPage::class))]
    #[Route(path: '/api/v1/book/{id}/review', methods: ['GET'])]
    final public function reviews(int $id, Request $request): Response
    {
        return $this->json($this->reviewService->getReviewPageByBookId(
            $id,
            $request->query->get('page', 1)
        ));
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Service\BooksCategoryService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class BookCategoryController extends AbstractController
{
    public function __construct(
        private readonly BooksCategoryService $bookCategoryService
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Return book categories',
        content: new Model(type: BookCategoryListResponse::class)
    )]
    #[Route(path: '/api/v1/book/categories', name: 'apiBookCategory', methods: ['GET'])]
    final public function categories(): Response
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}

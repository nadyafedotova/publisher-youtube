<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Model\ErrorResponse;
use App\Service\BooksService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BooksService $bookService
    ) {
    }

    /**
     * @throws HttpException
     */
    #[OA\Response(
        response: 200,
        description: 'Returned books inside a category',
        content: new Model(type: BookCategoryListResponse::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Book category not found',
        content: new Model(type: ErrorResponse::class)
    )
    ]
    #[Route(path: '/api/v1/category/{id}/books', methods: ['GET'])]
    final public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBooksByCategory($id));
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Model\BookChapterContentPage;
use App\Model\BookDetails;
use App\Model\ErrorResponse;
use App\Service\BookContentService;
use App\Service\BooksService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BooksService $bookService,
        private readonly BookContentService $bookContentService
    ) {
    }

    /** @throws HttpException */
    #[OA\Response(response: 200, description: 'Returned published books inside a category', content: new Model(type: BookCategoryListResponse::class))]
    #[OA\Response(response: 404, description: 'Book category not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/category/{id}/books', methods: ['GET'])]
    final public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBooksByCategory($id));
    }

    /** @throws HttpException */
    #[OA\Response(response: 200, description: 'Returned published book detail information', content: new Model(type: BookDetails::class))]
    #[OA\Response(response: 404, description: 'Book not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/book/{id}', methods: ['GET'])]
    final public function booksById(int $id): Response
    {
        return $this->json($this->bookService->getBookById($id));
    }

    #[OA\Response(response: 200, description: 'Get book chapter content', attachables: [new Model(type: BookChapterContentPage::class)])]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[Route(path: '/api/v1/book/{id}/chapter/{chapterId}/content', methods: ['GET'])]
    final public function chapterContent(Request $request, int $chapterId, int $id): Response
    {
        return $this->json($this->bookContentService->getPublishedContent($chapterId, (int) $request->query->get('page', 1)));
    }
}

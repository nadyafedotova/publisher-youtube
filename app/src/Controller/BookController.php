<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Model\BookCategoryListResponse;
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
    #[Route(path: '/api/v1/category/{id}/books')]
    final public function booksByCategory(int $id): Response
    {
        try {
            return $this->json($this->bookService->getBooksByCategory($id));
        } catch (BookCategoryNotFoundException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }
    }
}

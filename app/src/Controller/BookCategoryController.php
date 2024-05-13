<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BookCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(
        private BookCategoryService $bookCategoryService
    )
    {
    }

    #[Route('/api/v1/book/categories', name: 'apiBookCategory')]
    final public function categories(): Response
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}

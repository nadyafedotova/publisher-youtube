<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;

readonly class BooksCategoryService
{
    public function __construct(
        private BookCategoryRepository $bookCategoryRepository
    ) {
    }

    final public function getCategories(): BookCategoryListResponse
    {
        $categories = $this->bookCategoryRepository->findAllSortedByTitle();
        $items = array_map(
            fn (BookCategory $category) => new BookCategoryListItem(
                $category->getId(),
                $category->getTitle(),
                $category->getSlug()
            ),
            $categories
        );

        return new BookCategoryListResponse($items);
    }
}

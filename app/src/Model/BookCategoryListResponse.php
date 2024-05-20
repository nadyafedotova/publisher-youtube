<?php

declare(strict_types=1);

namespace App\Model;

readonly class BookCategoryListResponse
{
    /**
     * @param BookCategoryListItem[] $bookCategoryList
     */
    public function __construct(
        private array $bookCategoryList
    ) {
    }

    /**
     * @return BookCategoryListItem[]
     */
    final public function getBookCategoryList(): array
    {
        return $this->bookCategoryList;
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

readonly class BookCategoryListResponse
{
    /** @param BookCategory[] $bookCategoryList */
    public function __construct(
        private array $bookCategoryList
    ) {
    }

    /** @return BookCategory[] */
    final public function getBookCategoryList(): array
    {
        return $this->bookCategoryList;
    }
}

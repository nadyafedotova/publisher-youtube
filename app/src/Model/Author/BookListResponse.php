<?php

declare(strict_types=1);

namespace App\Model\Author;

readonly class BookListResponse
{
    /** @param BookListItem[] $bookCategoryList */
    public function __construct(
        private array $bookCategoryList
    ) {
    }

    /** @return BookListItem[] */
    final public function getBookCategoryList(): array
    {
        return $this->bookCategoryList;
    }
}

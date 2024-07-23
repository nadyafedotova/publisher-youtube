<?php

declare(strict_types=1);

namespace App\Model;

readonly class RecommendedBookListResponse
{
    /**
     * @param RecommendedBook[] $item
     */
    public function __construct(
        private array $item
    ) {
    }

    /**
     * @return BookListItem[]
     */
    final public function getItems(): array
    {
        return $this->item;
    }
}

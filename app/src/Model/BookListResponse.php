<?php

declare(strict_types=1);

namespace App\Model;

readonly class BookListResponse
{
    public function __construct(
        private array $items,
    ) {
    }

    /** @return BookListItem[] */
    final public function getItems(): array
    {
        return $this->items;
    }
}

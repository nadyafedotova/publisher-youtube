<?php

declare(strict_types=1);

namespace App\Model;

class BookChapterTreeResponse
{
    public function __construct(
        private array|BookChapter $items = []
    ) {
    }

    /**
     * @return BookChapter[]
     */
    final public function getItems(): array
    {
        return $this->items;
    }

    final public function addItem(BookChapter $chapter): void
    {
        $this->items[] = $chapter;
    }
}

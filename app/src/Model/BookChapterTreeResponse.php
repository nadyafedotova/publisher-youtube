<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\BookChapter;

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

    final public function addItem(BookChapter $items): self
    {
        $this->items[] = $items;

        return $this;
    }
}

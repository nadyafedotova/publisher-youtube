<?php

declare(strict_types=1);

namespace App\Model;

class BookChapter
{
    final public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private array $items = [],
    ) {
    }

    final public function getId(): int
    {
        return $this->id;
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function getSlug(): string
    {
        return $this->slug;
    }

    /** @return BookChapter[] */
    final public function getItems(): array
    {
        return $this->items;
    }

    final public function addItem(BookChapter $chapter): void
    {
        $this->items[] = $chapter;
    }
}

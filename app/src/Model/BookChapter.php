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

    final public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    final public function getSlug(): string
    {
        return $this->slug;
    }

    final public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return BookChapter[]
     */
    final public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param BookChapter[] $items
     */
    final public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    final public function addItem(BookChapter $chapter): void
    {
        $this->items[] = $chapter;
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

readonly class BookCategory
{
    public function __construct(
        private int    $id,
        private string $title,
        private string $slug
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
}

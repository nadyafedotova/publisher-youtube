<?php

declare(strict_types=1);

namespace App\Model;

class BookCategoryListItem
{
    private int $id;
    private string $title;
    private string $slug;

    public function __construct(int $id, string $title, string $slug)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
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

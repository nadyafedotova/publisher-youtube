<?php

declare(strict_types=1);

namespace App\Model\Author;

class UpdateBookRequest
{
    private ?string $title = null;

    private ?array $authors = [];

    private ?string $isbn = null;

    private ?string $description = null;

    /**
     * @var BookFormatOptions[]|null
     */
    private ?array $formats = [];

    /**
     * @var int[]|null
     */
    private ?array $categories = [];

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    final public function getAuthors(): ?array
    {
        return $this->authors;
    }

    final public function setAuthors(?array $authors): void
    {
        $this->authors = $authors;
    }

    final public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    final public function setIsbn(?string $isbn): void
    {
        $this->isbn = $isbn;
    }

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    final public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    final public function getFormats(): ?array
    {
        return $this->formats;
    }

    final public function setFormats(?array $formats): void
    {
        $this->formats = $formats;
    }

    final public function getCategories(): ?array
    {
        return $this->categories;
    }

    final public function setCategories(?array $categories): void
    {
        $this->categories = $categories;
    }
}

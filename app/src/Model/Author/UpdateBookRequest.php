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

    final public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    final public function getAuthors(): ?array
    {
        return $this->authors;
    }

    final public function setAuthors(?array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    final public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    final public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    final public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    final public function getFormats(): ?array
    {
        return $this->formats;
    }

    final public function setFormats(?array $formats): self
    {
        $this->formats = $formats;

        return $this;
    }

    final public function getCategories(): ?array
    {
        return $this->categories;
    }

    final public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}

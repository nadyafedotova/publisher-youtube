<?php

declare(strict_types=1);

namespace App\Model\Author;

use App\Entity\BookCategory;
use App\Entity\BookFormat;

class BookDetails
{
    private int $id;

    private string $title;

    private string $slug;

    private ?string $image;

    /**
     * @var string[]|null
     */
    private ?array $authors;

    private ?string $isbn;

    private ?string $description;

    private ?int $publicationDate;

    /**
     * @var BookCategory[]|null
     */
    private array $categories;

    /**
     * @var BookFormat[]|null
     */
    private array $formats;

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

    final public function getImage(): ?string
    {
        return $this->image;
    }

    final public function setImage(?string $image): self
    {
        $this->image = $image;

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

    final public function getPublicationDate(): ?int
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(?int $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    final public function getCategories(): array
    {
        return $this->categories;
    }

    final public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    final public function getFormats(): array
    {
        return $this->formats;
    }

    final public function setFormats(array $formats): self
    {
        $this->formats = $formats;

        return $this;
    }
}

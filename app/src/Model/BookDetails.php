<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\BookCategory;
use App\Entity\BookFormat;

class BookDetails
{
    private int $id;

    private string $title;

    private string $slug;

    private string $image;

    /**
     * @var string[]
     */
    private array $authors;

    private bool $meap;

    private int $publicationDate;

    private float $rating;

    private int $reviews;

    /**
     * @var BookCategory[]
     */
    private array $categories;

    /**
     * @var BookFormat[]
     */
    private array $formats;

    final public function getId(): int
    {
        return $this->id;
    }

    final public function setId(int $id): void
    {
        $this->id = $id;
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    final public function getSlug(): string
    {
        return $this->slug;
    }

    final public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    final public function getImage(): string
    {
        return $this->image;
    }

    final public function setImage(string $image): void
    {
        $this->image = $image;
    }

    final public function getAuthors(): array
    {
        return $this->authors;
    }

    final public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    final public function isMeap(): bool
    {
        return $this->meap;
    }

    final public function setMeap(bool $meap): void
    {
        $this->meap = $meap;
    }

    final public function getPublicationDate(): int
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(int $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    final public function getRating(): float
    {
        return $this->rating;
    }

    final public function setRating(float $rating): void
    {
        $this->rating = $rating;
    }

    final public function getReviews(): int
    {
        return $this->reviews;
    }

    final public function setReviews(int $reviews): void
    {
        $this->reviews = $reviews;
    }

    final public function getCategories(): array
    {
        return $this->categories;
    }

    final public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    final public function getFormats(): array
    {
        return $this->formats;
    }

    final public function setFormats(array $formats): void
    {
        $this->formats = $formats;
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\BookCategory;
use App\Entity\BookFormat;

class BookDetails extends BaseBookDetails
{
    private float $rating;

    private int $reviews;

    /** @var BookCategory[]|null */
    private array $categories = [];

    /** @var BookFormat[]|null */
    private array $formats = [];

    private array $chapters;

    final public function getRating(): float
    {
        return $this->rating;
    }

    final public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    final public function getReviews(): int
    {
        return $this->reviews;
    }

    final public function setReviews(int $reviews): self
    {
        $this->reviews = $reviews;

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

    final public function getChapters(): array
    {
        return $this->chapters;
    }

    /** @param BookChapter[] $chapters*/
    final public function setChapters(array $chapters): BookDetails
    {
        $this->chapters = $chapters;

        return $this;
    }
}

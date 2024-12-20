<?php

declare(strict_types=1);

namespace App\Model;

class ReviewPage
{
    /**
     * @var Review[]
     */
    private array $items;
    private float $rating;
    private int $page;
    private int $pages;
    private int $perPage;
    private int $total;

    final public function getItems(): array
    {
        return $this->items;
    }

    final public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    final public function getRating(): float
    {
        return $this->rating;
    }

    final public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    final public function getPage(): int
    {
        return $this->page;
    }

    final public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    final public function getPages(): int
    {
        return $this->pages;
    }

    final public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    final public function getPerPage(): int
    {
        return $this->perPage;
    }

    final public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    final public function getTotal(): int
    {
        return $this->total;
    }

    final public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
}

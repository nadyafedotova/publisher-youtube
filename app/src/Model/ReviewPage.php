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

    final public function setItems(array $items): void
    {
        $this->items = $items;
    }

    final public function getRating(): float
    {
        return $this->rating;
    }

    final public function setRating(float $rating): void
    {
        $this->rating = $rating;
    }

    final public function getPage(): int
    {
        return $this->page;
    }

    final public function setPage(int $page): void
    {
        $this->page = $page;
    }

    final public function getPages(): int
    {
        return $this->pages;
    }

    final public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }

    final public function getPerPage(): int
    {
        return $this->perPage;
    }

    final public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    final public function getTotal(): int
    {
        return $this->total;
    }

    final public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}

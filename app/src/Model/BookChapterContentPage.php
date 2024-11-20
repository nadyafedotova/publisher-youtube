<?php

declare(strict_types=1);

namespace App\Model;

class BookChapterContentPage
{
    /** @var BookChapterContent[] */
    private array $items;
    private int $page;
    private int $pages;
    private int $perPage;
    private int $total;

    /** @return BookChapterContent[] $items */
    final public function getItems(): array
    {
        return $this->items;
    }

    /** @param BookChapterContent[] $items */
    final public function setItems(array $items): self
    {
        $this->items = $items;

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

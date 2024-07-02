<?php

declare(strict_types=1);

namespace App\Model;

class BookListItem
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

    /**
     * @return string[]
     */
    final public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param string[] $authors
     * @return void
     */
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
}

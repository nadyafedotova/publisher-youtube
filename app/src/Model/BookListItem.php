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

    final public function getImage(): string
    {
        return $this->image;
    }

    final public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
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
    final public function setAuthors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    final public function isMeap(): bool
    {
        return $this->meap;
    }

    final public function setMeap(bool $meap): self
    {
        $this->meap = $meap;

        return $this;
    }

    final public function getPublicationDate(): int
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(int $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
}

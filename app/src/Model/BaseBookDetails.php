<?php

declare(strict_types=1);

namespace App\Model;

class BaseBookDetails
{
    private int $id;

    private string $title;

    private string $slug;

    private ?string $image = null;

    /**
     * @var string[]
     */
    private ?array $authors;

    private ?int $publicationDate = null;

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

    final public function getPublicationDate(): ?int
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(?int $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
}

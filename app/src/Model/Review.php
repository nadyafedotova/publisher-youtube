<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;

class Review
{
    private int $id;

    private string $content;

    private string $author;

    private int $rating;

    private DateTimeImmutable $createdAt;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    final public function getContent(): string
    {
        return $this->content;
    }

    final public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    final public function getAuthor(): string
    {
        return $this->author;
    }

    final public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    final public function getRating(): int
    {
        return $this->rating;
    }

    final public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    final public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

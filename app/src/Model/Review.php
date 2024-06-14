<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Book;
use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

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

    final public function setId(int $id): void
    {
        $this->id = $id;
    }

    final public function getContent(): string
    {
        return $this->content;
    }

    final public function setContent(string $content): void
    {
        $this->content = $content;
    }

    final public function getAuthor(): string
    {
        return $this->author;
    }

    final public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    final public function getRating(): int
    {
        return $this->rating;
    }

    final public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    final public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

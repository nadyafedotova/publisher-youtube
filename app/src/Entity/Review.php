<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $rating;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'string', length: 255)]
    private string $author;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    private Book $book;

    final public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(?int $id): void
    {
        $this->id = $id;
    }

    final public function getRating(): int
    {
        return $this->rating;
    }

    final public function setRating(int $rating): void
    {
        $this->rating = $rating;
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

    final public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    final public function getBook(): Book
    {
        return $this->book;
    }

    final public function setBook(Book $book): void
    {
        $this->book = $book;
    }
}

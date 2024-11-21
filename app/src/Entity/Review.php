<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

//#[ORM\HasLifecycleCallbacks]
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

//    #[ORM\PrePersist]
//    final public function setCreatedAtValue(): self
//    {
//        $this->createdAt = new DateTimeImmutable();
//
//        return $this;
//    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(?int $id): self
    {
        $this->id = $id;

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

    final public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    final public function getBook(): Book
    {
        return $this->book;
    }

    final public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}

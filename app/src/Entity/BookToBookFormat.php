<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BookToBookFormatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookToBookFormatRepository::class)]
class BookToBookFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $discountPercent;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'formats')]
    private Book $book;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: BookFormat::class, fetch: 'EAGER')]
    private BookFormat $format;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(?int $id): void
    {
        $this->id = $id;
    }

    final public function getPrice(): float
    {
        return $this->price;
    }

    final public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    final public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    final public function setDiscountPercent(?int $discountPercent): void
    {
        $this->discountPercent = $discountPercent;
    }

    final public function getBook(): Book
    {
        return $this->book;
    }

    final public function setBook(Book $book): void
    {
        $this->book = $book;
    }

    final public function getFormat(): BookFormat
    {
        return $this->format;
    }

    final public function setFormat(BookFormat $format): void
    {
        $this->format = $format;
    }
}

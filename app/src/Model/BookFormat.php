<?php

declare(strict_types=1);

namespace App\Model;

class BookFormat
{
    private int $id;

    private string $title;

    private ?string $description;

    private ?string $comment;

    private float $price;

    private ?int $discountPercent;

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

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    final public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    final public function getComment(): ?string
    {
        return $this->comment;
    }

    final public function setComment(?string $comment): void
    {
        $this->comment = $comment;
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
}

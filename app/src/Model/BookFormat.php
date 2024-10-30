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

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    final public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    final public function getComment(): ?string
    {
        return $this->comment;
    }

    final public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    final public function getPrice(): float
    {
        return $this->price;
    }

    final public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    final public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    final public function setDiscountPercent(?int $discountPercent): self
    {
        $this->discountPercent = $discountPercent;

        return $this;
    }
}

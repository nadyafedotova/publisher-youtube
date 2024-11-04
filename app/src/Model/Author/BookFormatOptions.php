<?php

declare(strict_types=1);

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class BookFormatOptions
{
    #[NotBlank]
    private int $id;

    #[NotBlank]
    #[Positive]
    private float $price;

    private ?int $discountPercent = null;

    final public function getId(): int
    {
        return $this->id;
    }

    final public function setId(int $id): void
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
}

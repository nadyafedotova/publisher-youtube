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

    final public function setId(int $id): self
    {
        $this->id = $id;

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

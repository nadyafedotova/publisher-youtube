<?php

declare(strict_types=1);

namespace App\Model;

readonly class Rating
{
    public function __construct(
        private int $total,
        private float $rating,
    ) {
    }

    final public function getTotal(): int
    {
        return $this->total;
    }

    final public function getRating(): float
    {
        return $this->rating;
    }
}

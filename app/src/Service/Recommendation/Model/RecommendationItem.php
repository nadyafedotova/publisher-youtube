<?php

declare(strict_types=1);

namespace App\Service\Recommendation\Model;

readonly class RecommendationItem
{
    public function __construct(
        private int $id,
    ) {
    }

    final public function getId(): int
    {
        return $this->id;
    }
}

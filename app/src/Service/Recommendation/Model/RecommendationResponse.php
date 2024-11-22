<?php

declare(strict_types=1);

namespace App\Service\Recommendation\Model;

class RecommendationResponse
{
    public function __construct(
        public int $id,
        public int $ts,
        public array $recommendations,
    ) {
    }

    final public function getId(): int
    {
        return $this->id;
    }

    final public function getTs(): int
    {
        return $this->ts;
    }

    /** @return RecommendationItem[] */
    final public function getRecommendations(): array
    {
        return $this->recommendations;
    }
}

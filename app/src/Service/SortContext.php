<?php

declare(strict_types=1);

namespace App\Service;

readonly class SortContext
{
    public function __construct(
        private SortPosition $position,
        private int          $nearId,
    ) {
    }

    final public static function fromNeighbours(?int $nextId, ?int $previousId): self
    {
        $position = match (true) {
            null === $previousId && null !== $nextId => SortPosition::AsFirst,
            null !== $previousId && null === $nextId => SortPosition::AsLast,
            default => SortPosition::Between,
        };

        return new self($position, SortPosition::AsLast === $position ? $previousId : $nextId);
    }

    final public function getPosition(): SortPosition
    {
        return $this->position;
    }

    final public function getNearId(): int
    {
        return $this->nearId;
    }
}

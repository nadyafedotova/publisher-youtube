<?php

declare(strict_types=1);

namespace App\Model\Author;


use App\Validation\AtLeastOneRequired;
use Symfony\Component\Validator\Constraints\Positive;

#[AtLeastOneRequired(['nextId', 'previousId'])]
class UpdateBookChapterSortRequest
{
    #[Positive]
    private ?int $nextId = null;
    #[Positive]
    private ?int $previousId = null;

    final public function getNextId(): ?int
    {
        return $this->nextId;
    }

    final public function setNextId(?int $nextId): self
    {
        $this->nextId = $nextId;

        return $this;
    }

    final public function getPreviousId(): ?int
    {
        return $this->previousId;
    }

    final public function setPreviousId(?int $previousId): self
    {
        $this->previousId = $previousId;

        return $this;
    }
}

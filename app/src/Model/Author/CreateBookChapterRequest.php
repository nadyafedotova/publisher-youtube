<?php

declare(strict_types=1);

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class CreateBookChapterRequest
{
    #[NotBlank]
    private string $title;

    #[Positive]
    private ?int $parentId = null;

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    final public function getParentId(): ?int
    {
        return $this->parentId;
    }

    final public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class UpdateBookChapterRequest
{
    #[Positive]
    private int $id;
    #[NotBlank]
    private string $title;

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
}

<?php

declare(strict_types=1);

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

class CreateBookChapterContentRequest
{
    #[NotBlank]
    private string $content;
    private ?bool $isPublished = false;

    final public function getContent(): string
    {
        return $this->content;
    }

    final public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    final public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    final public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}

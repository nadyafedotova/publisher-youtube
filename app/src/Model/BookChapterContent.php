<?php

declare(strict_types=1);

namespace App\Model;

class BookChapterContent
{
    private int $id;
    private string $content;
    private bool $isPublished;

    final public function getId(): int
    {
        return $this->id;
    }

    final public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    final public function getContent(): string
    {
        return $this->content;
    }

    final public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    final public function isPublished(): bool
    {
        return $this->isPublished;
    }

    final public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}

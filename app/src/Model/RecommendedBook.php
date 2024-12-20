<?php

declare(strict_types=1);

namespace App\Model;

class RecommendedBook
{
    private int $id;
    private string $title;
    private string $slug;
    private string $image;
    private string $shortDescription;

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

    final public function getSlug(): string
    {
        return $this->slug;
    }

    final public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    final public function getImage(): string
    {
        return $this->image;
    }

    final public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    final public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    final public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}

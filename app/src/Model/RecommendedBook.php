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

    final public function setId(int $id): void
    {
        $this->id = $id;
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    final public function getSlug(): string
    {
        return $this->slug;
    }

    final public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    final public function getImage(): string
    {
        return $this->image;
    }

    final public function setImage(string $image): void
    {
        $this->image = $image;
    }

    final public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    final public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }
}

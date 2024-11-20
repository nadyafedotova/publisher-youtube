<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookContentRepository;

#[ORM\Entity(repositoryClass: BookContentRepository::class)]
class BookContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $content;
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isPublished = false;
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: BookChapter::class)]
    private BookChapter $chapter;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(?int $id): self
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

    final public function getChapter(): BookChapter
    {
        return $this->chapter;
    }

    final public function setChapter(BookChapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }
}

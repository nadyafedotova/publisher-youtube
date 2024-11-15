<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BookChapterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookChapterRepository::class)]
class BookChapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private mixed $slug;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $sort = 0;

    #[ORM\Column(type: 'integer')]
    private int $level;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Book::class)]
    private Book $book;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: BookChapter::class)]
    private ?BookChapter $parent;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    final public function getTitle(): ?string
    {
        return $this->title;
    }

    final public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    final public function getSlug(): mixed
    {
        return $this->slug;
    }

    final public function setSlug(mixed $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    final public function getBook(): Book
    {
        return $this->book;
    }

    final public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    final public function getSort(): int
    {
        return $this->sort;
    }

    final public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    final public function getLevel(): int
    {
        return $this->level;
    }

    final public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    final public function getParent(): ?BookChapter
    {
        return $this->parent;
    }

    final public function setParent(?BookChapter $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    final public function hasParent(): bool
    {
        return null !== $this->parent;
    }
}

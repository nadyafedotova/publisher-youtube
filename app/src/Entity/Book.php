<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(length: 255)]
    private string $image;

    #[ORM\Column(type: 'simple_array')]
    private array $authors;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $publicationDate;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $meap;

    /**
     * @var Collection<BookCategory>
     */
    #[ORM\ManyToMany(targetEntity: BookCategory::class)]
    #[ORM\JoinTable(name: 'book_to_book_category')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    final public function getTitle(): ?string
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

    final public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param string[] $authors
     * @return $this
     */
    final public function setAuthors(array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    final public function getPublicationDate(): DateTimeInterface
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    final public function isMeap(): bool
    {
        return $this->meap;
    }

    final public function setMeap(bool $meap): self
    {
        $this->meap = $meap;

        return $this;
    }

    /**
     * @return Collection<BookCategory>
     */
    final public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection<BookCategory> $categories
     * @return $this
     */
    final public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}

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

    #[ORM\Column(type: 'string', length: 13)]
    private string $isbn;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeInterface $publicationDate;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $meap;

    /**
     * @var Collection<BookCategory>
     */
    #[ORM\ManyToMany(targetEntity: BookCategory::class)]
    #[ORM\JoinTable(name: 'book_to_book_category')]
    private Collection $categories;

    /**
     * @var Collection<BookToBookFormat>
     */
    #[ORM\OneToMany(targetEntity: BookToBookFormat::class, mappedBy: 'book')]
    private Collection $formats;

    /**
     * @var Collection<Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'book')]
    private Collection $reviews;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->formats = new ArrayCollection();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getTitle(): ?string
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

    final public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param string[] $authors
     */
    final public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    final public function getPublicationDate(): DateTimeInterface
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(DateTimeInterface $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    final public function isMeap(): bool
    {
        return $this->meap;
    }

    final public function setMeap(bool $meap): void
    {
        $this->meap = $meap;
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
     */
    final public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }

    final public function getIsbn(): string
    {
        return $this->isbn;
    }

    final public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    final public function getDescription(): string
    {
        return $this->description;
    }

    final public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    final public function getFormats(): Collection
    {
        return $this->formats;
    }

    final public function setFormats(Collection $formats): void
    {
        $this->formats = $formats;
    }

    final public function getReviews(): Collection
    {
        return $this->reviews;
    }

    final public function setReviews(Collection $reviews): void
    {
        $this->reviews = $reviews;
    }
}

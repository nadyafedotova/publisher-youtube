<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Author\PublishBookRequest;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private mixed $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private ?array $authors;

    #[ORM\Column(type: 'string', length: 13, nullable: true)]
    private ?string $isbn;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeInterface|PublishBookRequest|null $publicationDate;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'books')]
    private UserInterface $user;

    /** @var Collection<BookCategory> */
    #[ORM\ManyToMany(targetEntity: BookCategory::class)]
    #[ORM\JoinTable(name: 'book_to_book_category')]
    private Collection $categories;

    /** @var Collection<BookToBookFormat> */
    #[ORM\OneToMany(targetEntity: BookToBookFormat::class, mappedBy: 'book')]
    private Collection $formats;

    /** @var Collection<Review> */
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

    final public function getImage(): ?string
    {
        return $this->image;
    }

    final public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    final public function getAuthors(): ?array
    {
        return $this->authors;
    }

    final public function setAuthors(?array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    final public function getPublicationDate(): DateTimeInterface|PublishBookRequest|null
    {
        return $this->publicationDate;
    }

    final public function setPublicationDate(DateTimeInterface|PublishBookRequest|null $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /** @return Collection<BookCategory> */
    final public function getCategories(): Collection
    {
        return $this->categories;
    }

    /** @param Collection<BookCategory> $categories */
    final public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    final public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    final public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    final public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    final public function getFormats(): Collection
    {
        return $this->formats;
    }

    final public function setFormats(Collection $formats): self
    {
        $this->formats = $formats;

        return $this;
    }

    final public function getReviews(): Collection
    {
        return $this->reviews;
    }

    final public function setReviews(Collection $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    final public function getUser(): UserInterface
    {
        return $this->user;
    }

    final public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Author;

use App\Entity\BookCategory;
use App\Entity\BookFormat;
use App\Model\BaseBookDetails;

class BookDetails extends BaseBookDetails
{
    private ?string $isbn = null;

    private ?string $description = null;

    private ?int $publicationDate;

    /**
     * @var BookCategory[]|null
     */
    private array $categories = [];

    /**
     * @var BookFormat[]|null
     */
    private array $formats = [];

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

    final public function getCategories(): array
    {
        return $this->categories;
    }

    final public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    final public function getFormats(): array
    {
        return $this->formats;
    }

    final public function setFormats(array $formats): self
    {
        $this->formats = $formats;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Author\PublishBookRequest;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class BookPublishService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
    ) {
    }

    final public function publish(int $id, PublishBookRequest $publishBookRequest): void
    {
        $this->setPublicationDate($id, $publishBookRequest->getDateTime());
    }

    final public function unpublish(int $id): void
    {
        $this->setPublicationDate($id, null);
    }

    final public function setPublicationDate(int $id, DateTimeInterface|PublishBookRequest|null $dateTime): void
    {
        $book = $this->bookRepository->getBookById($id);
        $book->setPublicationDate($dateTime);

        $this->entityManager->flush();
    }
}

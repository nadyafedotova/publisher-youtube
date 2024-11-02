<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\IdResponse;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository         $bookRepository,
        private SluggerInterface       $slugger,
        private Security               $security,
        private UploadService          $uploadService,
    ) {
    }

    final public function uploadCover(int $id, UploadedFile $file): UploadCoverResponse
    {
        $book = $this->bookRepository->getUserBookById($id, $this->security->getUser());
        $oldImage = $book->getImage();
        $link = $this->uploadService->uploadBookFile($id, $file);

        $book->setImage($link);
        $this->entityManager->flush();

        if (null !== $oldImage) {
            $this->uploadService->deleteBookFile($book->getId(), basename($oldImage));
        }

        return new UploadCoverResponse($link);
    }

    final public function publish(int $id, PublishBookRequest $publishBookRequest): void
    {
        $this->setPublicationDate($id, $publishBookRequest->getDateTime());
    }

    final public function unpublish(int $id): void
    {
        $this->setPublicationDate($id, null);
    }

    public function getBooks(): BookListResponse
    {
        return new BookListResponse(
            array_map(
                [$this, 'map'],
                $this->bookRepository->findUserBooks($this->security->getUser()),
            ),
        );
    }

    final public function createBook(CreateBookRequest $createBookRequest): IdResponse
    {
        $slug = $this->slugger->slug($createBookRequest->getTitle());
        if ($this->bookRepository->existsBySlug($slug)) {
            throw new BookAlreadyExistsException();
        }

        $book = (new Book())
            ->setTitle($createBookRequest->getTitle())
            ->setMeap(false)
            ->setSlug($slug)
            ->setUser($this->security->getUser());

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new IdResponse($book->getId());
    }

    final public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->getUserBookById($id, $this->security->getUser());

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    final public function setPublicationDate(int $id, DateTimeInterface|PublishBookRequest|null $dateTime): void
    {
        $book = $this->bookRepository->getUserBookById($id, $this->security->getUser());
        $book->setPublicationDate($dateTime);

        $this->entityManager->flush();
    }

    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage());
    }
}

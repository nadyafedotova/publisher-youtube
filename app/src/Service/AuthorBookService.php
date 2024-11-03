<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\IdResponse;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class AuthorBookService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository         $bookRepository,
        private SluggerInterface       $slugger,
        private UploadService          $uploadService,
    ) {
    }

    final public function uploadCover(int $id, UploadedFile $file): UploadCoverResponse
    {
        $book = $this->bookRepository->getBookById($id);
        $oldImage = $book->getImage();
        $link = $this->uploadService->uploadBookFile($id, $file);

        $book->setImage($link);
        $this->entityManager->flush();

        if (null !== $oldImage) {
            $this->uploadService->deleteBookFile($book->getId(), basename($oldImage));
        }

        return new UploadCoverResponse($link);
    }

    public function getBooks(UserInterface $user): BookListResponse
    {
        return new BookListResponse(
            array_map(
                [$this, 'map'],
                $this->bookRepository->findUserBooks($user),
            ),
        );
    }

    final public function createBook(CreateBookRequest $createBookRequest, UserInterface $user): IdResponse
    {
        $slug = $this->slugger->slug($createBookRequest->getTitle());
        if ($this->bookRepository->existsBySlug($slug)) {
            throw new BookAlreadyExistsException();
        }

        $book = (new Book())
            ->setTitle($createBookRequest->getTitle())
            ->setMeap(false)
            ->setSlug($slug)
            ->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new IdResponse($book->getId());
    }

    final public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->getBookById($id);

        $this->entityManager->remove($book);
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

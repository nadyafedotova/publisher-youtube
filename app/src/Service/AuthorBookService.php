<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookToBookFormat;
use App\Exception\BookAlreadyExistsException;
use App\Mapper\BookMapper;
use App\Model\Author\BookDetails;
use App\Model\Author\BookFormatOptions;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\BaseBookDetails;
use App\Model\IdResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BooKFormatRepository;
use App\Repository\BookRepository;
use App\Repository\BookToBookFormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class AuthorBookService
{
    public function __construct(
        private BookRepository             $bookRepository,
        private BookFormatRepository       $bookFormatRepository,
        private BookToBookFormatRepository $bookToBookFormatRepository,
        private BookCategoryRepository     $bookCategoryRepository,
        private SluggerInterface           $slugger,
        private UploadService              $uploadService,
    ) {
    }

    final public function uploadCover(int $id, UploadedFile $file): UploadCoverResponse
    {
        $book = $this->bookRepository->getBookById($id);
        $oldImage = $book->getImage();
        $link = $this->uploadService->uploadBookFile($id, $file);

        $book->setImage($link);
        $this->bookRepository->commit();

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
        $slug = $this->slugifyOfThrow($createBookRequest->getTitle());
        $book = (new Book())
            ->setTitle($createBookRequest->getTitle())
            ->setSlug($slug)
            ->setUser($user);

        $this->bookRepository->saveAndCommit($book);

        return new IdResponse($book->getId());
    }

    final public function getBook(int $id): BaseBookDetails
    {
        $book = $this->bookRepository->getBookById($id);

        $bookDetails = (new BookDetails())
            ->setIsbn($book->getIsbn())
            ->setDescription($book->getDescription())
            ->setFormats(BookMapper::mapFormats($book))
            ->setCategories(BookMapper::mapCategories($book));

        return BookMapper::map($book, $bookDetails);
    }

    final public function updateBook(int $id, UpdateBookRequest $updateBookRequest): void
    {
        $book = $this->bookRepository->getBookById($id);
        $title = $updateBookRequest->getTitle();
        if (!empty($title)) {
            $book->setTitle($title)->setSlug($this->slugifyOfThrow($title, $id));
        }

        $formats = array_map(function (BookFormatOptions $bookFormatOptions) use ($book): BookToBookFormat {
            $format = (new BookToBookFormat())
                ->setPrice($bookFormatOptions->getPrice())
                ->setDiscountPercent($bookFormatOptions->getDiscountPercent())
                ->setBook($book)
                ->setFormat($this->bookFormatRepository->getById($bookFormatOptions->getId()));

            $this->bookToBookFormatRepository->save($format);

            return $format;
        }, $updateBookRequest->getFormats());

        foreach ($book->getFormats() as $format) {
            $this->bookToBookFormatRepository->remove($format);
        }

        $book->setAuthors($updateBookRequest->getAuthors())
            ->setIsbn($updateBookRequest->getIsbn())
            ->setDescription($updateBookRequest->getDescription())
            ->setCategories(new ArrayCollection(
                $this->bookCategoryRepository->findBookCategoriesByIds($updateBookRequest->getCategories())
            ))
            ->setFormats(new ArrayCollection($formats));

        $this->bookRepository->commit();
    }

    final public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->getBookById($id);
        $this->bookRepository->removeAndCommit($book);
    }

    private function slugifyOfThrow(string $title, ?int $id = null): AbstractUnicodeString
    {
        $slug = $this->slugger->slug($title);
        if ($this->bookRepository->existsBySlug($slug, $id)) {
            throw new BookAlreadyExistsException();
        }

        return $slug;
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

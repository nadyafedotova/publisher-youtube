<?php

namespace App\Tests\src\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BooksService;
use App\Tests\AbstractTestCase;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookServiceTest extends AbstractTestCase
{
    /**
     * @throws Exception
     */
    final public function testGetBooksByCategoryNotFound(): void
    {
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);

        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BooksService($bookRepository, $bookCategoryRepository, $reviewRepository))->getBooksByCategory(130);
    }

    /**
     * @throws Exception|ReflectionException
     */
    final public function testGetBooksByCategory(): void
    {
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $service = new BooksService($bookRepository, $bookCategoryRepository, $reviewRepository);
        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }

    /**
     * @throws ReflectionException
     */
    private function createBookEntity(): Book
    {
        $book = new Book();
        $book->setTitle('Test Book');
        $book->setSlug('test-book');
        $book->setMeap(false);
        $book->setIsbn('123321');
        $book->setDescription('RxJava for Android Developers');
        $book->setAuthors(['Tester']);
        $book->setImage('');
        $book->setCategories(new ArrayCollection());
        $book->setPublicationDate(new DateTimeImmutable('2020-10-10'));
        $this->setEntityId($book, 123);

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        $publicationDate = (new DateTimeImmutable('2020-10-10'))->getTimestamp();
        $bookListItem = new BookListItem();
        $bookListItem->setTitle('Test Book');
        $bookListItem->setSlug('test-book');
        $bookListItem->setMeap(false);
        $bookListItem->setAuthors(['Tester']);
        $bookListItem->setImage('');
        $bookListItem->setPublicationDate($publicationDate);

        return $bookListItem;
    }
}

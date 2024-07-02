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
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookServiceTest extends AbstractTestCase
{

    private ReviewRepository $reviewRepository;
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;
    private RatingService $ratingService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }

    final public function testGetBooksByCategoryNotFound(): void
    {

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        $this->createBookService()->getBooksByCategory(130);
    }

    /**
     * @throws ReflectionException
     */
    final public function testGetBooksByCategory(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $expected = new BookListResponse([$this->createBookItemModel()]);
        $this->assertEquals($expected, $this->createBookService()->getBooksByCategory(130));
    }

    private function createBookService(): BooksService
    {
        return new BooksService($this->bookRepository, $this->bookCategoryRepository, $this->reviewRepository, $this->ratingService);
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

    /**
     * @throws ReflectionException
     */
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
        $this->setEntityId($bookListItem, 123);

        return $bookListItem;
    }
}

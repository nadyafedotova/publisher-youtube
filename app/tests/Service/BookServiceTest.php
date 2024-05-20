<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BooksService;
use App\Tests\AbstractTestCase;
use DateTime;
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
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);

        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(130)
            ->willThrowException(new BookCategoryNotFoundException());

        $this->expectException(BookCategoryNotFoundException::class);

        (new BooksService($bookRepository, $bookCategoryRepository))->getBooksByCategory(130);
    }

    /**
     * @throws Exception|ReflectionException
     */
    final public function testGetBooksByCategory(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(130)
            ->willReturn(new BookCategory());

        $service = new BooksService($bookRepository, $bookCategoryRepository);
        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }

    /**
     * @throws ReflectionException
     */
    private function createBookEntity(): Book
    {
        $book = (new Book())
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setImage('')
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new DateTime('2020-10-10'));

        $this->setEntityId($book, 123);

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        $publicationDate = (new DateTime('2020-10-10'))->getTimestamp();
        return (new BookListItem())
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setImage('')
            ->setPublicationDate($publicationDate);
    }
}

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
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
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
     * @throws Exception
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

    private function createBookEntity(): Book
    {
        return (new Book())
            ->setId(123)
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setImage('')
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new DateTime('2020-10-10'));
    }

    private function createBookItemModel(): BookListItem
    {
        $publicationDate = (new DateTime('2020-10-10'))->getTimestamp();
        return (new BookListItem())
            ->setId(123)
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setImage('')
            ->setPublicationDate($publicationDate);
    }
}

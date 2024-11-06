<?php

namespace App\Tests\src\Service;

use App\Exception\BookCategoryNotFoundException;
use App\Model\Author\BookListResponse;
use App\Model\Rating;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BooksService;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;
    private RatingService $ratingService;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

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
    final public function testGetBooksById(): void
    {
        $format = MockUtils::createBookFormat();
        $bookToBookFormat = MockUtils::createBookToBookFormat($format, MockUtils::createBook(''));
        $this->bookRepository->expects($this->once())
            ->method('getPublishedById')
            ->with(123)
            ->willReturn(MockUtils::createBook('', MockUtils::createBookCategory(), $bookToBookFormat));

        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(123)
            ->willReturn(new Rating(10, 5.5));

        $expected = MockUtils::createBookDetails(MockUtils::createBookFormatModel(), '');

        $this->assertEquals($expected, $this->createBookService()->getBookById(123));
    }

    /**
     * @throws ReflectionException
     */
    final public function testGetBooksByCategory(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findPublishedBooksByCategoryId')
            ->with(130)
            ->willReturn([MockUtils::createBook()]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $expected = new BookListResponse([MockUtils::createBookItemModel()]);

        $this->assertEquals($expected, $this->createBookService()->getBooksByCategory(130));
    }

    private function createBookService(): BooksService
    {
        return new BooksService($this->bookRepository, $this->bookCategoryRepository, $this->ratingService);
    }
}

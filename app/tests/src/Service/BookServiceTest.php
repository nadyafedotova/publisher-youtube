<?php

namespace App\Tests\src\Service;

use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListResponse;
use App\Model\Rating;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BooksService;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use App\Tests\EntityTest;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;
    private RatingService $ratingService;
    private EntityTest $entityTest;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
        $this->entityTest = new EntityTest();
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
        $format = $this->entityTest->createBookFormat();
        $bookToBookFormat = $this->entityTest->createBookToBookFormat($format, $this->entityTest->createBook(''));
        $this->bookRepository->expects($this->once())
            ->method('getById')
            ->with(123)
            ->willReturn($this->entityTest->createBook('', $this->entityTest->createBookCategory(), $bookToBookFormat));

        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(123)
            ->willReturn(new Rating(10, 5.5));

        $expected = $this->entityTest->createBookDetails($this->entityTest->createBookFormatModel(), '');

        $this->assertEquals($expected, $this->createBookService()->getBookById(123));
    }

    /**
     * @throws ReflectionException
     */
    final public function testGetBooksByCategory(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->entityTest->createBook()]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $expected = new BookListResponse([$this->entityTest->createBookItemModel()]);

        $this->assertEquals($expected, $this->createBookService()->getBooksByCategory(130));
    }

    private function createBookService(): BooksService
    {
        return new BooksService($this->bookRepository, $this->bookCategoryRepository, $this->ratingService);
    }
}

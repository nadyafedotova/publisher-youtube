<?php

namespace App\Tests\src\Service;

use App\Exception\BookCategoryNotFoundException;
use App\Model\Author\BookListResponse;
use App\Model\BookChapterTreeResponse;
use App\Model\Rating;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookChapterService;
use App\Service\BooksService;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use Random\RandomException;
use ReflectionException;

class BookServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;
    private RatingService $ratingService;
    private BookChapterService $bookChapterService;

    /** @throws Exception */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->bookChapterService = $this->createMock(BookChapterService::class);
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

    /** @throws ReflectionException|RandomException */
    final public function testGetBooksById(): void
    {
        $book = MockUtils::createBook();
        MockUtils::setEntityId($book, 1);

        $this->bookChapterService->expects($this->once())
            ->method('getChaptersTree')
            ->with($book)
            ->willReturn(new BookChapterTreeResponse());

        $format = MockUtils::createBookFormat();
        $bookToBookFormat = MockUtils::createBookToBookFormat($format, MockUtils::createBook());

        $this->bookRepository->expects($this->once())
            ->method('getPublishedById')
            ->with(123)
            ->willReturn($book->setCategories(new ArrayCollection([MockUtils::createBookCategory()]))->setFormats(new ArrayCollection([$bookToBookFormat])));

        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(123)
            ->willReturn(new Rating(10, 5.5));

        $expected = MockUtils::createBookDetails()->setFormats([MockUtils::createBookFormatModel()]);
        $db = $this->createBookService()->getBookById(123);
        $expected->setTitle($db->getTitle())
            ->setSlug($db->getSlug())
            ->setPublicationDate($db->getPublicationDate())
            ->setChapters($db->getChapters())
            ->setRating(5.5)
            ->setReviews(10);

        $this->assertEquals($expected, $db);
    }


    /** @throws RandomException|ReflectionException */
    final public function testGetBooksByCategory(): void
    {
        $book = MockUtils::createBook();
        MockUtils::setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('findPublishedBooksByCategoryId')
            ->with(130)
            ->willReturn([$book]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $expected = (new BookListResponse([MockUtils::createBookItemModel()]))->getBookCategoryList()[0];

        $db = $this->createBookService()->getBooksByCategory(130)->getBookCategoryList()[0];
        $expected->setSlug($db->getSlug())->setImage($db->getImage());

        $this->assertEquals($expected, $db);
    }

    private function createBookService(): BooksService
    {
        return new BooksService($this->bookRepository, $this->bookCategoryRepository, $this->bookChapterService, $this->ratingService);
    }
}

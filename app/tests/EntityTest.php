<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Model\BookCategory as BookCategoryModel;
use App\Entity\BookFormat;
use App\Model\BookFormat as BookFormatModel;
use App\Entity\BookToBookFormat;
use App\Entity\Review;
use App\Model\BookDetails;
use App\Model\BookListItem;
use App\Model\RecommendedBook;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;
use ReflectionException;

class EntityTest
{
    private const int PER_PAGE = 5;

    /**
     * @throws ReflectionException
     */
    final protected function setEntityId(object $entity, int $value, string $idField = 'id'): void
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($idField);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);
    }

    /**
     * @throws ReflectionException
     */
    final public function createBook(
        string $title = 'Test Book',
        ?BookCategory $bookCategory = null,
        ?BookToBookFormat $format = null,
        string $description = 'RxJava for Android Developers',
        bool $mapper = false
    ): Book {
        $book = new Book();
        $book->setTitle($title);
        $book->setImage('');
        $book->setMeap(true);
        $book->setIsbn('123321');
        $book->setDescription($description);
        $book->setPublicationDate((new DateTimeImmutable())->setTimestamp(1602288000));
        $book->setAuthors(['Tester']);
        $book->setSlug('test-book');
        $this->setEntityId($book, 1);
        $book->setImage(''); //http://localhost/' . $title . 'png

        if (!$mapper) {
            $book->setFormats($format ? new ArrayCollection([$format]) : new ArrayCollection());
            $book->setCategories($bookCategory ? new ArrayCollection([$bookCategory]) : new ArrayCollection());
        }

        return $book;
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookCategory(): BookCategory
    {
        $bookCategory = new BookCategory();
        $this->setEntityId($bookCategory, 1);
        $bookCategory->setTitle('Test');
        $bookCategory->setSlug('test');

        return $bookCategory;
    }

    final public function createBookDetails(BookFormatModel|array $format, string $title = 'Test Book', bool $mapper = false): BookDetails
    {
        $bookDetails = new BookDetails();
        $bookDetails->setId(1);
        $bookDetails->setTitle($title);
        $bookDetails->setSlug('test-book');
        $bookDetails->setImage('');
        $bookDetails->setAuthors(['Tester']);
        $bookDetails->setMeap(true);
        $bookDetails->setPublicationDate(1602288000);

        if (!$mapper) {
            $bookDetails->setCategories([
                new BookCategoryModel(1, 'Test', 'test'),
            ]);
            $bookDetails->setFormats([$format]);
            $bookDetails->setRating(5.5);
            $bookDetails->setReviews(10);
        }

        return $bookDetails;
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookFormat(): BookFormat
    {
        $format = new BookFormat();
        $format->setTitle('format');
        $format->setDescription('Description format');
        $format->setComment(null);
        $this->setEntityId($format, 1);

        return $format;
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookFormatModel(): BookFormatModel
    {
        $format = new BookFormatModel();
        $format->setTitle('format');
        $format->setDescription('Description format');
        $format->setComment(null);
        $format->setPrice(123.55);
        $format->setDiscountPercent(5);
        $this->setEntityId($format, 1);

        return $format;
    }

    final public function createBookToBookFormat(BookFormat $format, Book $book): BookToBookFormat
    {
        $bookToBookFormat = new BookToBookFormat();
        $bookToBookFormat->setPrice(123.55);
        $bookToBookFormat->setDiscountPercent(5);
        $bookToBookFormat->setBook($book);
        $bookToBookFormat->setFormat($format);

        return $bookToBookFormat;
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookItemModel(): BookListItem
    {
        $publicationDate = (new DateTimeImmutable('2020-10-10'))->getTimestamp();
        $bookListItem = new BookListItem();
        $bookListItem->setTitle('Test Book');
        $bookListItem->setSlug('test-book');
        $bookListItem->setMeap(true);
        $bookListItem->setAuthors(['Tester']);
        $bookListItem->setImage('');
        $bookListItem->setPublicationDate($publicationDate);
        $this->setEntityId($bookListItem, 1);

        return $bookListItem;
    }

    /**
     * @throws ReflectionException
     */
    final public function createReview(Book $book): Review
    {
        $review = new Review();
        $review->setAuthor('tester');
        $review->setContent('test content');
        $review->setRating(5);
        $review->setBook($book);
        $this->setEntityId($review, 1);

        return $review;
    }

    final public function createReviewModel(): ReviewModel
    {
        $reviewModel = new ReviewModel();
        $reviewModel->setId(1);
        $reviewModel->setRating(4);
        $reviewModel->setContent('test');
        $reviewModel->setAuthor('tester');

        return $reviewModel;
    }

    final public function createReviewPage(
        int $total,
        float $rating,
        int $page,
        int $pages,
        ReviewModel|array|null $reviewModel
    ): ReviewPage {
        $reviewPage = new ReviewPage();
        $reviewPage->setTotal($total);
        $reviewPage->setRating($rating);
        $reviewPage->setPage($page);
        $reviewPage->setPages($pages);
        $reviewPage->setPerPage(self::PER_PAGE);
        $reviewPage->setItems($reviewModel);

        return $reviewPage;
    }

    final public function createRecommendedBook(string $expectedDescription, string $title = 'Test Recommended Book'): RecommendedBook
    {
        $recommendedBook = new RecommendedBook();
        $recommendedBook->setId(1);
        $recommendedBook->setTitle($title);
        $recommendedBook->setSlug('test-book');
        $recommendedBook->setImage('');
        $recommendedBook->setShortDescription($expectedDescription);

        return $recommendedBook;
    }
}

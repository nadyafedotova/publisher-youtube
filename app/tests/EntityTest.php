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
    final protected function setEntityId(object $entity, int $value, string $idField = 'id'): object
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($idField);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);

        return $entity;
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
        $book = (new Book())
            ->setTitle($title)
            ->setImage('') //http://localhost/' . $title . 'png
            ->setMeap(true)
            ->setIsbn('123321')
            ->setDescription($description)
            ->setPublicationDate((new DateTimeImmutable())->setTimestamp(1602288000))
            ->setAuthors(['Tester'])
            ->setSlug('test-book');
        $this->setEntityId($book, 1);

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
        $bookCategory = (new BookCategory())
            ->setTitle('Test')
            ->setSlug('test');
        $this->setEntityId($bookCategory, 1);

        return $bookCategory;
    }

    final public function createBookDetails(BookFormatModel|array $format, string $title = 'Test Book', bool $mapper = false): BookDetails
    {
        $bookDetails = (new BookDetails())
            ->setId(1)
            ->setTitle($title)
            ->setSlug('test-book')
            ->setImage('')
            ->setAuthors(['Tester'])
            ->setMeap(true)
            ->setPublicationDate(1602288000);

        if (!$mapper) {
            $bookDetails ->setCategories([new BookCategoryModel(1, 'Test', 'test')])
                ->setFormats([$format])
                ->setRating(5.5)
                ->setReviews(10);
        }

        return $bookDetails;
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookFormat(): BookFormat
    {
        $format = (new BookFormat())
            ->setTitle('format')
            ->setDescription('Description format')
            ->setComment(null);
        $this->setEntityId($format, 1);

        return $format;
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookFormatModel(): BookFormatModel
    {
        $format = (new BookFormatModel())
            ->setTitle('format')
            ->setDescription('Description format')
            ->setComment(null)
            ->setPrice(123.55)
            ->setDiscountPercent(5);
        $this->setEntityId($format, 1);

        return $format;
    }

    final public function createBookToBookFormat(BookFormat $format, Book $book): BookToBookFormat
    {
        return (new BookToBookFormat())
            ->setPrice(123.55)
            ->setDiscountPercent(5)
            ->setBook($book)
            ->setFormat($format);
    }

    /**
     * @throws ReflectionException
     */
    final public function createBookItemModel(): BookListItem
    {
        $bookListItem = (new BookListItem())
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(true)
            ->setAuthors(['Tester'])
            ->setImage('')
            ->setPublicationDate((new DateTimeImmutable('2020-10-10'))->getTimestamp());
        $this->setEntityId($bookListItem, 1);

        return $bookListItem;
    }

    /**
     * @throws ReflectionException
     */
    final public function createReview(Book $book): Review
    {
        $review = (new Review())
            ->setAuthor('tester')
            ->setContent('test content')
            ->setRating(5)
            ->setBook($book);
        $this->setEntityId($review, 1);

        return $review;
    }

    final public function createReviewModel(): ReviewModel
    {
        return (new ReviewModel())
            ->setId(1)
            ->setRating(4)
            ->setContent('test')
            ->setAuthor('tester');
    }

    final public function createReviewPage(
        int $total,
        float $rating,
        int $page,
        int $pages,
        ReviewModel|array|null $reviewModel
    ): ReviewPage {
        return (new ReviewPage())
            ->setTotal($total)
            ->setRating($rating)
            ->setPage($page)
            ->setPages($pages)
            ->setPerPage(self::PER_PAGE)
            ->setItems($reviewModel);
    }

    final public function createRecommendedBook(string $expectedDescription, string $title = 'Test Recommended Book'): RecommendedBook
    {
        return (new RecommendedBook())
            ->setId(1)
            ->setTitle($title)
            ->setSlug('test-book')
            ->setImage('')
            ->setShortDescription($expectedDescription);
    }
}

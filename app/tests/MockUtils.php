<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookFormat;
use App\Entity\BookToBookFormat;
use App\Entity\Review;
use App\Entity\User;
use App\Model\Author\BookListItem;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\BookFormat as BookFormatModel;
use App\Model\RecommendedBook;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Random\RandomException;
use ReflectionClass;
use ReflectionException;

class MockUtils
{
    private const int PER_PAGE = 5;

    /**
     * @throws ReflectionException
     */
    final public static function setEntityId(object $entity, int $value, string $idField = 'id'): object
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($idField);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);

        return $entity;
    }

    /**
     * @throws RandomException
     */
    public static function createUser(): User
    {
        $randomNumber = random_int(1, 999999999999999);

        return (new User())
            ->setEmail('test' . $randomNumber . '@test.com')
            ->setFirstName('Test')
            ->setLastName('Testerov')
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword('password');
    }

    /**
     * @throws ReflectionException|RandomException
     */
    final public static function createBook(
        string $title = 'Test Book',
        ?BookCategory $bookCategory = null,
        ?BookToBookFormat $format = null,
        string $description = 'RxJava for Android Developers',
        bool $mapper = false,
    ): Book {
        $randomNumber = random_int(1, 999999999999999);

        $book = (new Book())
            ->setTitle($title)
            ->setImage('') //http://localhost/' . $title . 'png
            ->setIsbn('123321')
            ->setDescription($description)
            ->setPublicationDate((new DateTimeImmutable())->setTimestamp(1602288000))
            ->setAuthors(['Tester'])
            ->setSlug('test' . $randomNumber . 'book')
            ->setUser(self::createUser());
        self::setEntityId($book, 1);

        if (!$mapper) {
            $book->setFormats($format ? new ArrayCollection([$format]) : new ArrayCollection());
            $book->setCategories($bookCategory ? new ArrayCollection([$bookCategory]) : new ArrayCollection());
        }

        return $book;
    }

    /**
     * @throws ReflectionException
     */
    final public static function createBookCategory(): BookCategory
    {
        $bookCategory = (new BookCategory())
            ->setTitle('Test')
            ->setSlug('test');
        self::setEntityId($bookCategory, 1);

        return $bookCategory;
    }

    final public static function createBookDetails(BookFormatModel|array $format, string $title = 'Test Book', bool $mapper = false): BookDetails
    {
        $bookDetails = (new BookDetails())
            ->setId(1)
            ->setTitle($title)
            ->setSlug('test-book')
            ->setImage('')
            ->setAuthors(['Tester'])
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
    final public static function createBookFormat(): BookFormat
    {
        $format = (new BookFormat())
            ->setTitle('format')
            ->setDescription('Description format')
            ->setComment(null);
        self::setEntityId($format, 1);

        return $format;
    }

    /**
     * @throws ReflectionException
     */
    final public static function createBookFormatModel(): BookFormatModel
    {
        $format = (new BookFormatModel())
            ->setTitle('format')
            ->setDescription('Description format')
            ->setComment(null)
            ->setPrice(123.55)
            ->setDiscountPercent(5);
        self::setEntityId($format, 1);

        return $format;
    }

    final public static function createBookToBookFormat(BookFormat $format, Book $book): BookToBookFormat
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
    final public static function createBookItemModel(): BookListItem
    {
        $bookListItem = (new BookListItem())
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setImage('');
        self::setEntityId($bookListItem, 1);

        return $bookListItem;
    }

    /**
     * @throws ReflectionException
     */
    final public static function createReview(Book $book): Review
    {
        $review = (new Review())
            ->setAuthor('tester')
            ->setContent('test content')
            ->setRating(5)
            ->setBook($book)
            ->setCreatedAt(new DateTimeImmutable());
        self::setEntityId($review, 1);

        return $review;
    }

    final public static function createReviewModel(): ReviewModel
    {
        return (new ReviewModel())
            ->setId(1)
            ->setRating(4)
            ->setContent('test')
            ->setAuthor('tester');
    }

    final public static function createReviewPage(
        int $total,
        float $rating,
        int $page,
        int $pages,
        ReviewModel|array|null $reviewModel,
    ): ReviewPage {
        return (new ReviewPage())
            ->setTotal($total)
            ->setRating($rating)
            ->setPage($page)
            ->setPages($pages)
            ->setPerPage(self::PER_PAGE)
            ->setItems($reviewModel);
    }

    final public static function createRecommendedBook(string $expectedDescription, string $title = 'Test Recommended Book'): RecommendedBook
    {
        return (new RecommendedBook())
            ->setId(1)
            ->setTitle($title)
            ->setSlug('test-book')
            ->setImage('')
            ->setShortDescription($expectedDescription);
    }

    final public static function createBookFormatLink(Book $book, BookFormat $bookFormat): BookToBookFormat
    {
        return (new BookToBookFormat())
            ->setPrice(123.55)
            ->setFormat($bookFormat)
            ->setDiscountPercent(5)
            ->setBook($book);
    }
}

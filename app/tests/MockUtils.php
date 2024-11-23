<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookChapter;
use App\Entity\BookContent;
use App\Entity\BookFormat;
use App\Entity\BookToBookFormat;
use App\Entity\Review;
use App\Entity\User;
use App\Model\BookListItem;
use App\Model\BookDetails;
use App\Model\BookFormat as BookFormatModel;
use App\Model\RecommendedBook;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Model\SingUpRequest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Random\RandomException;
use ReflectionClass;
use ReflectionException;

class MockUtils
{
    private const int PER_PAGE = 5;

    /**  @throws ReflectionException */
    final public static function setEntityId(object $entity, int $value, string $idField = 'id'): object
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($idField);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);

        return $entity;
    }

    /** @throws RandomException */
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

    /** @throws RandomException */
    public static function createBook(): Book
    {
        return (new Book())
            ->setTitle('Test book')
            ->setImage('http://localhost.png')
            ->setIsbn('123321')
            ->setDescription('test')
            ->setPublicationDate(new \DateTimeImmutable('2020-10-10'))
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection([]))
            ->setSlug('test-book');
        //  ->setUser(self::createUser());
    }

    /** @throws ReflectionException */
    final public static function createBookCategory(): BookCategory
    {
        $bookCategory = (new BookCategory())
            ->setTitle('Test')
            ->setSlug('test');
        self::setEntityId($bookCategory, 1);

        return $bookCategory;
    }

    final public static function createBookDetails(): BookDetails
    {
        return (new BookDetails())
            ->setId(1)
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setImage('')
            ->setAuthors(['Tester'])
            ->setPublicationDate(1602288000);
    }

    /** @throws ReflectionException */
    final public static function createBookFormat(): BookFormat
    {
        $format = (new BookFormat())
            ->setTitle('format')
            ->setDescription('Description format')
            ->setComment(null);
        self::setEntityId($format, 1);

        return $format;
    }

    /** @throws ReflectionException */
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

    final public static function createBookItemModel(): BookListItem
    {
        return (new BookListItem())
            ->setId(1)
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setAuthors(['Tester'])
            ->setImage('http://localhost.png')
            ->setPublicationDate(1602288000);
    }

    /** @throws ReflectionException */
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

    final public static function createReviewPage(): ReviewPage
    {
        return (new ReviewPage());
    }

    final public static function createRecommendedBook(): RecommendedBook
    {
        return (new RecommendedBook())
            ->setId(1)
            ->setTitle('Test Recommended Book')
            ->setSlug('test-book')
            ->setImage('')
            ->setShortDescription('');
    }

    final public static function createBookFormatLink(Book $book, BookFormat $bookFormat): BookToBookFormat
    {
        return (new BookToBookFormat())
            ->setPrice(123.55)
            ->setFormat($bookFormat)
            ->setDiscountPercent(5)
            ->setBook($book);
    }

    /**@throws RandomException */
    public static function createBookChapter(Book $book): BookChapter
    {
        $randomNumber = random_int(1, 999999999999999);

        return (new BookChapter())
            ->setTitle('Chapter')
            ->setBook($book)
            ->setSlug('test-'.$randomNumber.'chapter')
            ->setLevel(1)
            ->setSort(1)
            ->setParent(null);
    }

    public static function createBookContent(BookChapter $chapter): BookContent
    {
        return (new BookContent())
            ->setContent('testing content')
            ->setIsPublished(true)
            ->setChapter($chapter);
    }

    public static function createSingUpRequest(): SingUpRequest
    {
        return (new SingUpRequest())
            ->setFirstName('Tester')
            ->setLastName('Tester')
            ->setEmail('tester@test.com')
            ->setPassword('testtest');
    }
}

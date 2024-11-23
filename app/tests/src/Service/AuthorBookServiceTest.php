<?php

namespace App\Tests\src\Service;

use App\Entity\Book;
use App\Entity\BookToBookFormat;
use App\Entity\User;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookDetails;
use App\Model\Author\BookFormatOptions;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\BookCategory;
use App\Model\BookFormat;
use App\Model\IdResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BooKFormatRepository;
use App\Repository\BookRepository;
use App\Repository\BookToBookFormatRepository;
use App\Service\AuthorBookService;
use App\Service\UploadService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;
use Random\RandomException;
use ReflectionException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class AuthorBookServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;
    private BooKFormatRepository $bookFormatRepository;
    private BookToBookFormatRepository $bookToBookFormatRepository;
    private BookCategoryRepository $bookCategoryRepository;
    private SluggerInterface $slugger;
    private UploadService $uploadService;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookFormatRepository = $this->createMock(BooKFormatRepository::class);
        $this->bookToBookFormatRepository = $this->createMock(BookToBookFormatRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->slugger = $this->createMock(SluggerInterface::class);
        $this->uploadService = $this->createMock(UploadService::class);
    }

    final public function testDeleteBook(): void
    {
        $book = new Book();

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('removeAndCommit')
            ->with($book);

        $this->createService()->deleteBook(1);
    }

    final public function testCreateBook(): void
    {
        $payload = new CreateBookRequest();
        $payload->setTitle('New Book');
        $user = new User();

        $expectedBook = (new Book())->setTitle('New Book')
            ->setSlug('new-book')
            ->setUser($user);

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('New Book')
            ->willReturn(new UnicodeString('new-book'));

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('new-book')
            ->willReturn(false);

        $this->bookRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedBook)
            ->willReturnCallback(function (Book $book) {
                MockUtils::setEntityId($book, 1);
            });

        $this->assertEquals(new IdResponse(1), $this->createService()->createBook($payload, $user));
    }

    final public function testCreateBookSlugExistsException(): void
    {
        $this->expectException(BookAlreadyExistsException::class);

        $payload = new CreateBookRequest();
        $payload->setTitle('New Book');
        $user = new User();

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('New Book')
            ->willReturn(new UnicodeString('new-book'));

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('new-book')
            ->willReturn(true);

        $this->assertEquals(new IdResponse(1), $this->createService()->createBook($payload, $user));
    }

    /** @throws ReflectionException|RandomException */
    final public function testGetBook(): void
    {
        $category = MockUtils::createBookCategory();
        MockUtils::setEntityId($category, 1);

        $format = MockUtils::createBookFormat();
        MockUtils::setEntityId($format, 1);

        $book = MockUtils::createBook()->setCategories(new ArrayCollection([$category]));
        $bookLink = MockUtils::createBookFormatLink($book, $format);
        $book->setFormats(new ArrayCollection([$bookLink]));

        MockUtils::setEntityId($book, 1);

        $bookDetails = (new BookDetails())
            ->setId(1)
            ->setTitle('Test Book')
            ->setImage('')
            ->setIsbn('123321')
            ->setDescription('RxJava for Android Developers')
            ->setPublicationDate(1602288000)
            ->setAuthors(['Tester'])
            ->setCategories([
                new BookCategory(1, 'Devices', 'devices'),
            ])
            ->setFormats([
                    (new BookFormat())->setId(1)->setTitle('format')
                    ->setDescription('Description format')
                    ->setComment(null)
                    ->setPrice(123.55)
                    ->setDiscountPercent(5),
            ]);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $db = $this->createService()->getBook(1);
        $bookDetails->setSlug($db->getSlug());
        $this->assertEquals($bookDetails, $db);

    }

    /** @throws ReflectionException|RandomException */
    final public function testGetBooks(): void
    {
        $user = new User();
        $book = MockUtils::createBook();
        MockUtils::setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('findUserBooks')
            ->with($user)
            ->willReturn(([$book]));

        $bookItem = (new BookListItem())->setId(1)
            ->setImage('')
            ->setTitle('Test Book');

        $db = $this->createService()->getBooks($user);
        $bookItem->setSlug($db->getBookCategoryList()[0]->getSlug());

        $this->assertEquals(new BookListResponse([$bookItem]), $db);
    }

    final public function testUpdateBookExceptionOnDuplicateSlug(): void
    {
        $this->expectException(BookAlreadyExistsException::class);

        $book = new Book();
        $payload = (new UpdateBookRequest())->setTitle('Old');

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('Old')
            ->willReturn(new UnicodeString('old'));

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('old')
            ->willReturn(true);

        $this->createService()->updateBook(1, $payload);
    }

    /** @throws ReflectionException */
    final public function testUpdateBook(): void
    {
        $book = new Book();
        $bookToBookFormat = new BookToBookFormat();
        $book->setFormats(new ArrayCollection([$bookToBookFormat]));

        $category = MockUtils::createBookCategory();
        MockUtils::setEntityId($category, 1);

        $format = MockUtils::createBookFormat();
        MockUtils::setEntityId($format, 1);

        $newBookToBookFormat = (new BookToBookFormat())
            ->setBook($book)->setFormat($format)
            ->setPrice(123.5)->setDiscountPercent(5);

        $payload = (new UpdateBookRequest())->setTitle('Old')->setAuthors(['Tester'])
            ->setIsbn('isbn')
            ->setCategories([1])
            ->setFormats([
                (new BookFormatOptions())->setId(1)->setPrice(123.5)->setDiscountPercent(5)
            ])
            ->setDescription('Description');

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('Old')
            ->willReturn(new UnicodeString('old'));

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('old')
            ->willReturn(false);

        $this->bookCategoryRepository->expects($this->once())
            ->method('findBookCategoriesByIds')
            ->with([1])
            ->willReturn([$category]);

        $this->bookFormatRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($format);

        $this->bookToBookFormatRepository->expects($this->once())
            ->method('save')
            ->with($newBookToBookFormat);

        $this->bookToBookFormatRepository->expects($this->once())
            ->method('remove')
            ->with($bookToBookFormat);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        $this->createService()->updateBook(1, $payload);
    }

    /** @throws ReflectionException */
    final public function testUploadCover(): void
    {
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $book = (new Book())->setImage(null);
        MockUtils::setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        $this->uploadService->expects($this->once())
            ->method('uploadBookFile')
            ->with(1, $file)
            ->willReturn('http://localhost/new.jpg');

        $this->assertEquals(
            new UploadCoverResponse('http://localhost/new.jpg'),
            $this->createService()->uploadCover(1, $file),
        );
    }

    /**b @throws ReflectionException */
    final public function testUploadCoverRemoveOld(): void
    {
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $book = (new Book())->setImage('http://localhost/old.png');
        MockUtils::setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        $this->uploadService->expects($this->once())
            ->method('uploadBookFile')
            ->with(1, $file)
            ->willReturn('http://localhost/new.jpg');

        $this->uploadService->expects($this->once())
            ->method('deleteBookFile')
            ->with(1, 'old.png');

        $this->assertEquals(
            new UploadCoverResponse('http://localhost/new.jpg'),
            $this->createService()->uploadCover(1, $file),
        );
    }

    private function createService(): AuthorBookService
    {
        return new AuthorBookService(
            $this->bookRepository,
            $this->bookFormatRepository,
            $this->bookToBookFormatRepository,
            $this->bookCategoryRepository,
            $this->slugger,
            $this->uploadService,
        );
    }
}

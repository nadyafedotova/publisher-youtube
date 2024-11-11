<?php

namespace App\Tests\src\Service;

use App\Entity\Book;
use App\Model\Author\PublishBookRequest;
use App\Repository\BookRepository;
use App\Service\BookPublishService;
use App\Tests\AbstractTestCase;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;

class BookPublishServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
    }

    final public function testPublish(): void
    {
        $book = new Book();
        $datetime = new DateTimeImmutable('2020-01-01');
        $request = new PublishBookRequest();

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        (new BookPublishService($this->bookRepository))->publish(1, $request);

        $this->assertEquals($datetime, $book->getPublicationDate());
    }

    final public function testUnpublish(): void
    {
        $book = new Book();

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        (new BookPublishService($this->bookRepository))->unpublish(1);

        $this->assertNull($book->getPublicationDate());
    }
}

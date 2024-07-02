<?php

namespace App\Tests\src\Mapper;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use DateTimeImmutable;

class BookMapperTest extends AbstractTestCase
{

    /**
     * @throws \ReflectionException
     */
    public function testMap(): void
    {
        $book = new Book();
        $book->setTitle('title');
        $book->setSlug('slug');
        $book->setImage('123');
        $book->setAuthors(['tester']);
        $book->setMeap(true);
        $book->setPublicationDate(new DateTimeImmutable('2024-06-27'));

        $this->setEntityId($book, 1);

        $expected = new BookDetails();
        $expected->setId(1);
        $expected->setTitle('title');
        $expected->setSlug('slug');
        $expected->setImage('123');
        $expected->setAuthors(['tester']);
        $expected->setMeap(true);
        $expected->setPublicationDate(1719446400);

        self::assertEquals($expected, BookMapper::map($book, new BookDetails));
    }
}

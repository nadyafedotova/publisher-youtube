<?php

namespace App\Tests\src\Mapper;

use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use App\Tests\EntityTest;
use ReflectionException;

class BookMapperTest extends AbstractTestCase
{
    /**
     * @throws ReflectionException
     */
    final public function testMap(): void
    {
        $entityTest = new EntityTest();
        $book = $entityTest->createBook('', null, null, '', true);
        $expected = $entityTest->createBookDetails([], '', true);

        self::assertEquals($expected, BookMapper::map($book, new BookDetails));
    }
}

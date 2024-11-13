<?php

namespace App\Tests\src\Mapper;

use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Random\RandomException;
use ReflectionException;

class BookMapperTest extends AbstractTestCase
{
    /**
     * @throws ReflectionException|RandomException
     */
    final public function testMap(): void
    {
        $book = MockUtils::createBook('', null, null, '', true);
        $expected = MockUtils::createBookDetails([], '', true);

        $this->assertEquals($expected, BookMapper::map($book, new BookDetails));
    }
}

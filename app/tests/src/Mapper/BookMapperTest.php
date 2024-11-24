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
    /** @throws RandomException|ReflectionException */
    final public function testMap(): void
    {
        $book = MockUtils::createBook();
        MockUtils::setEntityId($book, 1);
        $expected = MockUtils::createBookDetails();
        $db = BookMapper::map($book, new BookDetails);
        $expected->setSlug($db->getSlug());

        $this->assertEquals($expected, BookMapper::map($book, new BookDetails));
    }
}

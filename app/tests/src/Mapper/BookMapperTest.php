<?php

namespace App\Tests\src\Mapper;

use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;

class BookMapperTest extends AbstractTestCase
{
    final public function testMap(): void
    {
        $book = MockUtils::createBook();
        $expected = MockUtils::createBookDetails();
        $db = BookMapper::map($book, new BookDetails);
        $expected->setSlug($db->getSlug());

        $this->assertEquals($expected, BookMapper::map($book, new BookDetails));
    }
}

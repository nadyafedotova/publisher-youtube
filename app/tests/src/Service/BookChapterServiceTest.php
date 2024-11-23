<?php

declare(strict_types=1);

namespace App\Tests\src\Service;

use App\Entity\Book;
use App\Model\BookChapter;
use App\Model\BookChapterTreeResponse;
use App\Repository\BookChapterRepository;
use App\Service\BookChapterService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookChapterServiceTest extends AbstractTestCase
{
    private BookChapterRepository $bookChapterRepository;

    /** @throws Exception */
    protected function setUp(): void
    {
        parent::setUp();

        $this->bookChapterRepository = $this->createMock(BookChapterRepository::class);
    }

    /** @throws ReflectionException|ReflectionException */
    final public function testChapterTest(): void
    {
        $book = new Book();
        $response = new BookChapterTreeResponse([
            new BookChapter(1, 'test chapter', 'test-chapter', [
                new BookChapter(2, 'test chapter', 'test-chapter'),
            ]),
        ]);

        $parentChapter = MockUtils::createBookChapter($book);
        MockUtils::setEntityId($parentChapter, 1);

        $childChapter = MockUtils::createBookChapter($book)->setParent($parentChapter);
        MockUtils::setEntityId($parentChapter, 2);

        $this->bookChapterRepository->expects($this->once())
            ->method('findSortedChaptersByBook')
            ->with($book)
            ->willReturn([$parentChapter, $childChapter]);

        $this->assertEquals($response, $this->createService()->getChaptersTree($book));
    }

    private function createService(): BookChapterService
    {
        return new BookChapterService($this->bookChapterRepository);
    }
}

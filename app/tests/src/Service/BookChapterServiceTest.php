<?php

declare(strict_types=1);

namespace App\Tests\src\Service;

use App\Entity\Book;
use App\Model\BookChapter as BookChapterModel;
use App\Model\BookChapterTreeResponse;
use App\Repository\BookChapterRepository;
use App\Service\BookChapterService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use PHPUnit\Framework\MockObject\Exception;
use Random\RandomException;
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

    /** @throws ReflectionException|ReflectionException|RandomException */
    public function testGetChaptersTree(): void
    {
        $book = new Book();

        $parentChapter = MockUtils::createBookChapter($book);
        MockUtils::setEntityId($parentChapter, 1);

        $childChapter = MockUtils::createBookChapter($book)->setParent($parentChapter);
        MockUtils::setEntityId($childChapter, 2);

        $response = new BookChapterTreeResponse([
            new BookChapterModel(1, $parentChapter->getTitle(), $parentChapter->getSlug(), [
                new BookChapterModel(2, $childChapter->getTitle(), $childChapter->getSlug()),
            ]),
        ]);


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

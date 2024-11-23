<?php

declare(strict_types=1);

namespace App\Tests\src\Service;

use App\Entity\Book;
use App\Entity\BookChapter;
use App\Exception\BookChapterInvalidSortException;
use App\Model\Author\CreateBookChapterRequest;
use App\Model\Author\UpdateBookChapterSortRequest;
use App\Model\BookChapterTreeResponse;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookRepository;
use App\Service\AuthorBookChapterService;
use App\Service\BookChapterService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class AuthorBookChapterServiceTest extends AbstractTestCase
{
    private BookChapterRepository $bookChapterRepository;
    private BookRepository $bookRepository;
    private BookChapterService $bookChapterService;
    private SluggerInterface $slugger;

    /** @throws Exception */
    protected function setUp(): void
    {
        parent::setUp();

        $this->bookChapterRepository = $this->createMock(BookChapterRepository::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookChapterService =  $this->createMock(BookChapterService::class);
        $this->slugger = $this->createMock(SluggerInterface::class);
    }

    final public function testCreateChapterMaxLevelException(): void
    {
        $this->expectException(BookChapterInvalidSortException::class);

        $book = new Book();
        $parentBookChapter =(new BookChapter())->setLevel(3);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(2)
            ->willReturn($parentBookChapter);

        $payload = (new CreateBookChapterRequest())->setTitle('test')->setParentId(2);
        $this->createService()->createChapter($payload, 1);
    }

    final public function testCreateChapterNested(): void
    {
        $book = new Book();
        $parentBookChapter =(new BookChapter())->setLevel(1);

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(2)
            ->willReturn($parentBookChapter);

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('test')
            ->willReturn(new UnicodeString('test'));

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookChapterRepository->expects($this->once())
            ->method('getMaxSort')
            ->with(2)
            ->willReturn($parentBookChapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($this->callback(function (BookChapter $bookChapter) use ($book, $parentBookChapter) {
                $expectedChapter = (new BookChapter())
                    ->setBook($book)
                    ->setSort(6)
                    ->setLevel(2)
                    ->setTitle('test')
                    ->setSlug('test')
                    ->setParent($parentBookChapter);

                $this->assertEquals($expectedChapter, $bookChapter);
                MockUtils::setEntityId($bookChapter, 1);

                return true;
            }));

        $payload = (new CreateBookChapterRequest())->setTitle('test')->setParentId(2);

        $this->assertEquals(
            new IdResponse(1),
            $this->createService()->createChapter($payload, 1)
        );
    }

    final public function testCreateChapter(): void
    {
        $book = new Book();

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('test')
            ->willReturn(new UnicodeString('test'));

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookChapterRepository->expects($this->once())
            ->method('getMaxSort')
            ->with($book, 1)
            ->willReturn(5);

        $this->bookChapterRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($this->callback(function (BookChapter $bookChapter) use ($book) {
                $expectedChapter = (new BookChapter())
                    ->setBook($book)
                    ->setSort(6)
                    ->setLevel(2)
                    ->setTitle('test')
                    ->setSlug('test')
                    ->setParent(null);

                $this->assertEquals($expectedChapter, $bookChapter);
                MockUtils::setEntityId($bookChapter, 1);

                return true;
            }));

        $payload = (new CreateBookChapterRequest())->setTitle('test');

        $this->assertEquals(
            new IdResponse(1),
            $this->createService()->createChapter($payload, 1)
        );
    }

    final public function testUpdateChapter(): void
    {
        $bookChapter = new BookChapter();
        $newTitle ='update chapter';
        $newSlug ='update-chapter';

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with($newTitle)
            ->willReturn(new UnicodeString($newSlug));

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($bookChapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('commit');

        $payload = (new CreateBookChapterRequest())->setTitle($newTitle);
        $this->createService()->updateChapter($payload, 1);

        $this->assertEquals($newTitle, $bookChapter->getTitle());
        $this->assertEquals($newSlug, $bookChapter->getSlug());
    }

    final public function testDeleteChapter(): void
    {
        $chapter = new BookChapter();

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($chapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('removeAndCommit')
            ->with($chapter);

        $this->createService()->deleteChapter(1);
    }

    final public function testGetChaptersTree(): void
    {
        $treeResponse = new BookChapterTreeResponse();
        $book = new Book();

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookChapterService->expects($this->once())
            ->method('getChaptersTree')
            ->with(1)
            ->willReturn($treeResponse);

        $this->assertEquals($treeResponse, $this->createService()->getChaptersTree(1));
    }

    final public function testUpdateChapterSortAsLast(): void
    {
        $book = new Book();
        $parentChapter = new BookChapter();
        $chapter = (new BookChapter())->setBook($book)->setParent(null);
        $nearChapter = (new BookChapter())->setLevel(2)->setBook($book)->setParent($parentChapter);

        $params = [[1], [2]];
        $this->bookChapterRepository->expects($this->exactly(2))
            ->method('getById')
            ->willReturnCallback(function (string $param) use (&$params) {
                $this::assertSame(array_shift($params), $param);
            })
            ->willReturnOnConsecutiveCalls($chapter, $nearChapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('commit');

        $payload = (new UpdateBookChapterSortRequest())->setNextId(null)->setPreviousId(5);
        $this->createService()->updateChapterSort($payload, 1);

        $this->assertEquals(2, $chapter->getLevel());
        $this->assertEquals(6, $chapter->getSort());
        $this->assertEquals($parentChapter, $chapter->getParent());
    }

    final public function testUpdateChapterSortAsFirstOrBetween(): void
    {
        $book = new Book();
        $parentChapter = new BookChapter();
        $chapter = (new BookChapter())->setBook($book)->setParent(null);
        $nearChapter = (new BookChapter())->setLevel(2)->setBook($book)->setParent($parentChapter)->setSort(8);

        $params = [[1], [5]];
        $this->bookChapterRepository->expects($this->exactly(2))
            ->method('getById')
            ->willReturnCallback(function (string $param) use (&$params) {
                $this::assertSame(array_shift($params), $param);
            })
            ->willReturnOnConsecutiveCalls($chapter, $nearChapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('increasesSortFrom')
            ->with(8, $book, 2, 1);

        $this->bookChapterRepository->expects($this->once())
            ->method('commit');

        $payload = (new UpdateBookChapterSortRequest())->setNextId(5)->setPreviousId(null);
        $this->createService()->updateChapterSort($payload, 1);

        $this->assertEquals(2, $chapter->getLevel());
        $this->assertEquals(8, $chapter->getSort());
        $this->assertEquals($parentChapter, $chapter->getParent());
    }

    private function createService(): AuthorBookChapterService
    {
        return new AuthorBookChapterService($this->bookRepository, $this->bookChapterRepository, $this->bookChapterService, $this->slugger);
    }
}

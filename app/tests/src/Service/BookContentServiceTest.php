<?php

namespace App\Tests\src\Service;

use App\Entity\BookChapter;
use App\Entity\BookContent;
use App\Exception\BookChapterContentNotFoundException;
use App\Exception\BookChapterNotFoundException;
use App\Model\Author\CreateBookChapterContentRequest;
use App\Model\BookChapterContent;
use App\Model\BookChapterContentPage;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookContentRepository;
use App\Service\BookContentService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use ArrayIterator;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookContentServiceTest extends AbstractTestCase
{
    private const int PER_PAGE = 30;

    private BookChapterRepository $bookChapterRepository;
    private BookContentRepository $bookContentRepository;

    /** @throws Exception */
    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookChapterRepository = $this->createMock(BookChapterRepository::class);
        $this->bookContentRepository = $this->createMock(BookContentRepository::class);
    }

    final public function testCreateContentException(): void
    {
        $this->expectException(BookChapterNotFoundException::class);
        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willThrowException(new BookChapterNotFoundException());

        $this->createService()->createContent(new CreateBookChapterContentRequest(), 1);
    }

    final public function testCreateContent(): void
    {
        $payload = (new CreateBookChapterContentRequest())
            ->setContent('testing')
            ->setIsPublished(true);

        $chapter = new BookChapter();
        $expectedContent = (new BookContent())
            ->setContent('testing')
            ->setIsPublished(true)
            ->setChapter($chapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($chapter);

        $this->bookContentRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedContent)
            ->willReturnCallback(function (BookContent $content) {
                MockUtils::setEntityId($content, 2);
            });

        $this->assertEquals(new IdResponse(2), $this->createService()->createContent($payload, 1));
    }

    final public function testUpdateContentException(): void
    {
        $this->expectException(BookChapterContentNotFoundException::class);
        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willThrowException(new BookChapterContentNotFoundException());

        $this->createService()->createContent(new CreateBookChapterContentRequest(), 1);
    }

    final public function testUpdateContent(): void
    {
        $payload = (new CreateBookChapterContentRequest())
            ->setContent('initial')
            ->setIsPublished(false);

        $chapter = new BookChapter();
        $content = (new BookContent())->setChapter($chapter);

        $expectedContent = (new BookContent())
            ->setContent('initial')
            ->setIsPublished(false)
            ->setChapter($chapter);

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(2)
            ->willReturn($content);

        $this->bookContentRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedContent);

        $this->createService()->updateContent($payload, 2);
    }

    final public function testDeleteContentException(): void
    {
        $this->expectException(BookChapterContentNotFoundException::class);

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willThrowException(new BookChapterContentNotFoundException());

        $this->createService()->deleteContent(1);
    }

    final public function testDeleteContent(): void
    {
        $content = new BookContent();

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($content);

        $this->bookContentRepository->expects($this->once())
            ->method('removeAndCommit')
            ->with($content);

        $this->createService()->deleteContent(1);
    }

    /** @throws ReflectionException */
    final public function testGetAllContent(): void
    {
        $this->testGetContent(false);
    }

    /** @throws ReflectionException */
    final public function testGetPublishedContent(): void
    {
        $this->testGetContent(true);
    }

    /** @throws ReflectionException */
    private function testGetContent(bool $onlyPublished): void
    {
        $chapter = new BookChapter();
        $content = MockUtils::createBookContent($chapter)
            ->setIsPublished($onlyPublished);
        MockUtils::setEntityId($content, 1);

        $this->bookContentRepository->expects($this->once())
            ->method('getPageByChapterId')
            ->with(1, $onlyPublished, 0, self::PER_PAGE)
            ->willReturn(new ArrayIterator([$content]));

        $this->bookContentRepository->expects($this->once())
            ->method('countByChapterId')
            ->with(1, $onlyPublished)
            ->willReturn(1);

        $service = $this->createService();
        $result = $onlyPublished
            ? $service->getPublishedContent(1, 1)
            : $service->getAllContent(1, 1);

        $expected = MockUtils::createBookChapterContentPage($onlyPublished);

        $this->assertEquals($expected, $result);
    }

    private function createService(): BookContentService
    {
        return new BookContentService($this->bookContentRepository, $this->bookChapterRepository);
    }
}

<?php

namespace App\Tests\src\Service;

use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BooksCategoryService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookCategoryServiceTest extends AbstractTestCase
{
    /** @throws Exception|ReflectionException */
    final public function testGetCategories(): void
    {
        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([(new MockUtils())->createBookCategory()]);

        $slugger = $this->createMock(SluggerInterface::class);
        $service = new BooksCategoryService($repository, $slugger);

        $expectedResponse = new BookCategoryListResponse([new BookCategoryModel(1, 'Test', 'test')]);

        self::assertEquals($expectedResponse, $service->getCategories());
    }
}

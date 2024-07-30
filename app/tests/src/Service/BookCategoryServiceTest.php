<?php

namespace App\Tests\src\Service;

use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BooksCategoryService;
use App\Tests\AbstractTestCase;
use App\Tests\EntityTest;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookCategoryServiceTest extends AbstractTestCase
{
    /**
     * @throws Exception
     * @throws ReflectionException
     */
    final public function testGetCategories(): void
    {
        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([(new EntityTest())->createBookCategory()]);

        $service = new BooksCategoryService($repository);

        $expectedResponse = new BookCategoryListResponse([new BookCategoryModel(1, 'Test', 'test')]);

        self::assertEquals($expectedResponse, $service->getCategories());
    }
}

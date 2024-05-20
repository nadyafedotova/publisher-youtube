<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BooksCategoryService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookCategoryServiceTest extends AbstractTestCase
{
    /**
     * @throws Exception|ReflectionException
     */
    final public function testGetCategories(): void
    {
        $bookCategory = new BookCategory();
        $bookCategory->setTitle('Test')->setSlug('test');
        $this->setEntityId($bookCategory, 7);

        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([$bookCategory]);

        $service = new BooksCategoryService($repository);

        $expectedResponse = new BookCategoryListResponse([new BookCategoryListItem(7, 'Test', 'test')]);

        self::assertEquals($expectedResponse, $service->getCategories());
    }
}

<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BooksCategoryService;
use Doctrine\Common\Collections\Order;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BookCategoryServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    final public function testGetCategories(): void
    {
        $bookCategory = new BookCategory();
        $bookCategory->setId(7);
        $bookCategory->setTitle('Test')->setSlug('test');

        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with([], ['title' => 'ASC'])
            ->willReturn([$bookCategory]);

        $service = new BooksCategoryService($repository);

        $expectedResponse = new BookCategoryListResponse([new BookCategoryListItem(7, 'Test', 'test')]);

        self::assertEquals($expectedResponse, $service->getCategories());
    }
}

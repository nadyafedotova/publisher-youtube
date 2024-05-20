<?php

namespace App\Tests\Repository;

use App\Entity\BookCategory;
use App\Repository\BookCategoryRepository;
use App\Tests\AbstractRepositoryTest;

class BookCategoryRepositoryTest extends AbstractRepositoryTest
{
    private BookCategoryRepository $bookCategoryRepository;

    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookCategoryRepository = $this->getRepositoryForEntity(BookCategory::class);
    }

    final public function testFindAllSortedByTitle(): void
    {
        $devices = new BookCategory();
        $devices->setTitle('Devices')->setSlug('devices');

        $android = new BookCategory();
        $android->setTitle('Android')->setSlug('android');

        $computer = new BookCategory();
        $computer->setTitle('Computer')->setSlug('computer');

        foreach ([$devices, $android, $computer] as $category) {
            $this->entityManager->persist($category);
        }

        $this->entityManager->flush();

        $titles = array_map(
            fn (BookCategory $category) => $category->getTitle(),
            $this->bookCategoryRepository->findAllSortedByTitle()
        );
        $this->assertEquals(['Android', 'Computer', 'Devices'], $titles);
    }
}

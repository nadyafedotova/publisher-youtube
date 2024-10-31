<?php

namespace App\Tests\src\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;
use App\Tests\EntityTest;
use ReflectionException;

class BookRepositoryTest extends AbstractRepositoryTest
{
    private BookRepository $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    /**
     * @throws ReflectionException
     */
    final public function testFindBooksByCategoryId(): void
    {
        $entityTest = new EntityTest();
        $devicesCategory = $entityTest->createBookCategory();
        $this->entityManager->persist($devicesCategory);

        for ($i = 0; $i < 5; ++$i) {
            $book = $entityTest->createBook('device-' . $i, $devicesCategory);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(5, $this->bookRepository->findPublishedBooksByCategoryId($devicesCategory->getId()));
    }
}

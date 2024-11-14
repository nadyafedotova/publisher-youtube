<?php

namespace App\Tests\src\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;
use App\Tests\MockUtils;
use Random\RandomException;
use ReflectionException;

class BookRepositoryTest extends AbstractRepositoryTest
{
    private BookRepository $bookRepository;

    final protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    /**
     * @throws ReflectionException|RandomException
     */
    final public function testFindBooksByCategoryId(): void
    {
        $user = MockUtils::createUser();
        $this->bookRepository->save($user);

        $devicesCategory = MockUtils::createBookCategory();
        $this->bookRepository->save($devicesCategory);

        for ($i = 0; $i < 5; ++$i) {
            $book = MockUtils::createBook('device-' . $i, $devicesCategory);
            $this->bookRepository->save($book);
        }

        $this->bookRepository->commit();

        $this->assertCount(5, $this->bookRepository->findPublishedBooksByCategoryId($devicesCategory->getId()));
    }
}

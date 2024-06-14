<?php

namespace App\Tests\src\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookRepositoryTest extends AbstractRepositoryTest
{
    private BookRepository $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    final public function testFindBooksByCategoryId(): void
    {
        $devicesCategory = new BookCategory();
        $devicesCategory->setTitle('Devices');
        $devicesCategory->setSlug('devices');
        $this->entityManager->persist($devicesCategory);

        for ($i = 0; $i < 5; ++$i) {
            $book = $this->createBook('device-' . $i, $devicesCategory);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(5, $this->bookRepository->findBooksByCategoryId($devicesCategory->getId()));
    }

    private function createBook(string $title, BookCategory $bookCategory): Book
    {
        $book = new Book();
        $book->setPublicationDate(new DateTimeImmutable());
        $book->setAuthors(['author']);
        $book->setMeap(false);
        $book->setIsbn('123321');
        $book->setDescription('RxJava for Android Developers');
        $book->setSlug($title);
        $book->setCategories(new ArrayCollection([$bookCategory]));
        $book->setTitle($title);
        $book->setImage('http://localhost/' . $title . 'png');

        return $book;
    }
}

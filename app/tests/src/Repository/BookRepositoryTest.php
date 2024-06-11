<?php

namespace App\Tests\src\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;
use DateTime;
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
        $devicesCategory->setTitle('Devices')->setSlug('devices');
        $this->entityManager->persist($devicesCategory);

        for ($i = 0; $i < 5; ++$i) {
            $book = $this->createBook('device-'.$i, $devicesCategory);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(5, $this->bookRepository->findBooksByCategoryId($devicesCategory->getId()));
    }


    private function createBook(string $title, BookCategory $bookCategory): Book
    {
        return (new Book())
            ->setPublicationDate(new DateTime())
            ->setAuthors(['author'])
            ->setMeap(false)
            ->setSlug($title)
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setTitle($title)
            ->setImage('http://localhost/'. $title.'png');
    }
}

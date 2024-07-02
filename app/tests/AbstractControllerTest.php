<?php

namespace App\Tests;

use App\Entity\Book;
use App\Entity\BookCategory;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTest extends WebTestCase
{
    use JsonAssertions;

    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->em = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;

        restore_exception_handler();
    }

    protected function createBook(?BookCategory $bookCategory = null): Book
    {
        $book = new Book();
        $book->setTitle('Test Book');
        $book->setImage('');
        $book->setMeap(true);
        $book->setIsbn('123321');
        $book->setDescription('RxJava for Android Developers');
        $book->setPublicationDate(new DateTimeImmutable('now'));
        $book->setAuthors(['Tester']);
        $book->setCategories($bookCategory ? new ArrayCollection([$bookCategory]) : new ArrayCollection());
        $book->setSlug('test-book');

        $this->em->persist($book);
        $this->em->flush();

        return $book;
    }
}

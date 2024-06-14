<?php

namespace App\Tests\src\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Tests\AbstractControllerTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookControllerTest extends AbstractControllerTest
{
    final public function testBooksByCategory(): void
    {
        $categoryId = $this->createCategory();

        $this->client->request('GET', '/api/v1/category/' . $categoryId . '/books');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema(
            $responseContent,
            [
                'type' => 'object',
                'required' => ['bookCategoryList'],
                'properties' => [
                    'bookCategoryList' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                            'required' => ['id', 'title', 'slug', 'image', 'authors', 'meap', 'publicationDate'],
                            'properties' => [
                                'id' => ['type' => 'integer'],
                                'title' => ['type' => 'string'],
                                'slug' => ['type' => 'string'],
                                'image' => ['type' => 'string'],
                                'authors' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'string'],
                                ],
                                'publicationDate' => ['type' => 'integer'],
                                'meap' => ['type' => 'boolean'],
                            ],
                        ],
                    ],
                ],
            ],
        );
    }

    private function createCategory(): int
    {
        $bookCategory = new BookCategory();
        $bookCategory->setTitle('Devices');
        $bookCategory->setSlug('devices');
        $this->em->persist($bookCategory);
        $this->em->flush();

        $book = new Book();
        $book->setTitle('Test Book');
        $book->setImage('');
        $book->setMeap(true);
        $book->setIsbn('123321');
        $book->setDescription('RxJava for Android Developers');
        $book->setPublicationDate(new DateTimeImmutable('now'));
        $book->setAuthors(['Tester']);
        $book->setCategories(new ArrayCollection([$bookCategory]));
        $book->setSlug('test-book');
        $this->em->persist($book);

        return $bookCategory->getId();
    }
}

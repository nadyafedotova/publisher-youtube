<?php

namespace App\Tests\src\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Tests\AbstractControllerTest;
use App\Tests\EntityTest;
use ReflectionException;

class BookControllerTest extends AbstractControllerTest
{
    private EntityTest $entityTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityTest = new EntityTest();
    }

    final public function testBooksByCategory(): void
    {
        $categoryId = $this->createCategory()->getId();

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
                                'image' => ['type' => 'string', 'format' => 'uri'],
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

    /**
     * @throws ReflectionException
     */
    final public function testBookById(): void
    {
        $bookId = $this->createBookCategory()->getId();

        $this->client->request('GET', '/api/v1/book/' . $bookId);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => [
                'id', 'title', 'slug', 'image', 'authors', 'publicationDate', 'rating', 'reviews',
                'categories', 'formats',
            ],
            'properties' => [
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'id' => ['type' => 'integer'],
                'publicationDate' => ['type' => 'integer'],
                'image' => ['type' => 'string'],
                'meap' => ['type' => 'boolean'],

                'authors' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'rating' => ['type' => 'number'],
                'reviews' => ['type' => 'integer'],
                'categories' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
                'formats' => ['type' => 'array'],
            ],
        ]);
    }


    private function createCategory(): BookCategory
    {
        $bookCategory = $this->entityTest->createBookCategory();
        $this->em->persist($bookCategory);
        $this->em->flush();

        return $bookCategory;
    }

    /**
     * @throws ReflectionException
     */
    private function createBookCategory(): Book
    {
        $bookCategory = $this->createCategory();
        $book = $this->entityTest->createBook('', $bookCategory);
        $format = $this->entityTest->createBookFormat();
        $this->em->persist($book);
        $this->em->persist($format);

        $bookToBookFormat = $this->entityTest->createBookToBookFormat($format, $book);
        $this->em->persist($bookToBookFormat);
        $this->em->flush();

        return $book;
    }
}

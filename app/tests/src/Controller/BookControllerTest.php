<?php

namespace App\Tests\src\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Entity\BookFormat;
use App\Tests\AbstractControllerTest;

class BookControllerTest extends AbstractControllerTest
{
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

    public function testBookId(): void
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
        $bookCategory = new BookCategory();
        $bookCategory->setTitle('Devices');
        $bookCategory->setSlug('devices');

        $this->em->persist($bookCategory);
        $this->em->flush();

        return $bookCategory;
    }

    private function createBookCategory(): Book
    {
        $bookCategory = $this->createCategory();
        $book = $this->createBook($bookCategory);

        $format = new BookFormat();
        $format->setTitle('format');
        $format->setDescription('Description format');
        $format->setComment(null);

        $this->em->persist($format);

        $bookToBookFormat = new BookToBookFormat();
        $bookToBookFormat->setPrice(123.55);
        $bookToBookFormat->setDiscountPercent(5);
        $bookToBookFormat->setBook($book);
        $bookToBookFormat->setFormat($format);

        $this->em->persist($bookToBookFormat);
        $this->em->flush();

        return $book;
    }
}

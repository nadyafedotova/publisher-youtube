<?php

namespace App\Tests\src\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionException;

class BookControllerTest extends AbstractControllerTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @throws ReflectionException
     */
    final public function testBooksByCategory(): void
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);

        $book = MockUtils::createBook()
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setUser($user);
        $this->em->persist($book);
        $this->em->flush();

        $this->client->request('GET', '/api/v1/category/' . $bookCategory->getId() . '/books');
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
                            'required' => ['id', 'title', 'slug', 'image', 'authors', 'publicationDate'],
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
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);

        $format = MockUtils::createBookFormat();
        $this->em->persist($format);

        $book = MockUtils::createBook()
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setUser($user);
        $this->em->persist($book);
        $this->em->persist(MockUtils::createBookFormatLink($book, $format));
        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/' . $book->getId());
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
}
